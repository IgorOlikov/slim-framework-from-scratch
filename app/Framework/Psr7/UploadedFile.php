<?php

namespace Framework\Psr7;

use Framework\Psr\Http\Message\StreamInterface;
use Framework\Psr\Http\Message\UploadedFileInterface;
use Framework\Psr7\Factory\StreamFactory;
use InvalidArgumentException;
use Override;
use RuntimeException;

class UploadedFile implements UploadedFileInterface
{
    /**
     * The client-provided full path to the file
     */
    protected string $file;

    /**
     * The client-provided file name.
     */
    protected ?string $name;

    /**
     * The client-provided media type of the file.
     */
    protected ?string $type;

    protected ?int $size;

    /**
     * A valid PHP UPLOAD_ERR_xxx code for the file upload.
     */
    protected int $error = UPLOAD_ERR_OK;

    /**
     * Indicates if the upload is from a SAPI environment.
     */
    protected bool $sapi = false;

    /**
     * @var StreamInterface|null
     */
    protected $stream;

    /**
     * Indicates if the uploaded file has already been moved.
     */
    protected bool $moved = false;

    final public function __construct(
        $fileNameOrStream,
        ?string $name = null,
        ?string $type = null,
        ?int $size = null,
        int $error = UPLOAD_ERR_OK,
        bool $sapi = false
    ) {
        if ($fileNameOrStream instanceof StreamInterface) {
            $file = $fileNameOrStream->getMetadata('uri');
            if (!is_string($file)) {
                throw new InvalidArgumentException('No URI associated with the stream.');
            }
            $this->file = $file;
            $this->stream = $fileNameOrStream;
        } elseif (is_string($fileNameOrStream)) {
            $this->file = $fileNameOrStream;
        } else {
            throw new InvalidArgumentException(
                'Please provide a string (full path to the uploaded file) or an instance of StreamInterface.'
            );
        }
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->error = $error;
        $this->sapi = $sapi;
    }

    #[Override] public function getStream(): StreamInterface
    {
        if ($this->moved) {
            throw new RuntimeException(sprintf('Uploaded file %s has already been moved', $this->name));
        }

        if (!$this->stream) {
            $this->stream = (new StreamFactory())->createStreamFromFile($this->file);
        }

        return $this->stream;
    }

    #[Override] public function moveTo(string $targetPath): void
    {
        if ($this->moved) {
            throw new RuntimeException('Uploaded file already moved');
        }

        $targetIsStream = strpos($targetPath, '://') > 0;
        if (!$targetIsStream && !is_writable(dirname($targetPath))) {
            throw new InvalidArgumentException('Upload target path is not writable');
        }

        if ($targetIsStream) {
            if (!copy($this->file, $targetPath)) {
                throw new RuntimeException(sprintf('Error moving uploaded file %s to %s', $this->name, $targetPath));
            }

            if (!unlink($this->file)) {
                throw new RuntimeException(sprintf('Error removing uploaded file %s', $this->name));
            }
        } elseif ($this->sapi) {
            if (!is_uploaded_file($this->file)) {
                throw new RuntimeException(sprintf('%s is not a valid uploaded file', $this->file));
            }

            if (!move_uploaded_file($this->file, $targetPath)) {
                throw new RuntimeException(sprintf('Error moving uploaded file %s to %s', $this->name, $targetPath));
            }
        } else {
            if (!rename($this->file, $targetPath)) {
                throw new RuntimeException(sprintf('Error moving uploaded file %s to %s', $this->name, $targetPath));
            }
        }

        $this->moved = true;
    }

    #[Override] public function getSize(): ?int
    {
        return $this->size;
    }

    #[Override] public function getError(): int
    {
        return $this->error;
    }

    #[Override] public function getClientFilename(): ?string
    {
        return $this->name;
    }

    #[Override] public function getClientMediaType(): ?string
    {
        return $this->type;
    }

    public function getFilePath(): string
    {
        return $this->file;
    }

    public static function createFromGlobals(array $globals): array
    {
        if (isset($globals['slim.files']) && is_array($globals['slim.files'])) {
            return $globals['slim.files'];
        }

        if (!empty($_FILES)) {
            return self::parseUploadedFiles($_FILES);
        }

        return [];
    }

    private static function parseUploadedFiles(array $uploadedFiles): array
    {
        $parsed = [];
        foreach ($uploadedFiles as $field => $uploadedFile) {
            if (!isset($uploadedFile['error'])) {
                if (is_array($uploadedFile)) {
                    $parsed[$field] = self::parseUploadedFiles($uploadedFile);
                }
                continue;
            }

            $parsed[$field] = [];
            if (!is_array($uploadedFile['error'])) {
                $parsed[$field] = new static(
                    $uploadedFile['tmp_name'],
                    $uploadedFile['name'] ?? null,
                    $uploadedFile['type'] ?? null,
                    $uploadedFile['size'] ?? null,
                    $uploadedFile['error'],
                    true
                );
            } else {
                $subArray = [];
                foreach ($uploadedFile['error'] as $fileIdx => $error) {
                    // Normalize sub array and re-parse to move the input's key name up a level
                    $subArray[$fileIdx]['name'] = $uploadedFile['name'][$fileIdx];
                    $subArray[$fileIdx]['type'] = $uploadedFile['type'][$fileIdx];
                    $subArray[$fileIdx]['tmp_name'] = $uploadedFile['tmp_name'][$fileIdx];
                    $subArray[$fileIdx]['error'] = $uploadedFile['error'][$fileIdx];
                    $subArray[$fileIdx]['size'] = $uploadedFile['size'][$fileIdx];

                    $parsed[$field] = self::parseUploadedFiles($subArray);
                }
            }
        }

        return $parsed;
    }

}