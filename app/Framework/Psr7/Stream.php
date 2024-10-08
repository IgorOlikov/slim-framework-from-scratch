<?php

namespace Framework\Psr7;

use Framework\Psr\Http\Message\StreamInterface;
use InvalidArgumentException;
use Override;
use RuntimeException;

class Stream implements StreamInterface
{
    public const FSTAT_MODE_S_IFIFO = 0010000;

    /**
     * The underlying stream resource
     *
     * @var resource|null
     */
    protected $stream;

    protected ?array $meta;

    protected ?bool $readable = null;

    protected ?bool $writable = null;

    protected ?bool $seekable = null;

    protected ?int $size = null;

    protected ?bool $isPipe = null;

    protected bool $finished = false;

    protected ?StreamInterface $cache;

    public function __construct($stream, ?StreamInterface $cache = null)
    {
        $this->attach($stream);

        if ($cache && (!$cache->isSeekable() || !$cache->isWritable())) {
            throw new RuntimeException('Cache stream must be seekable and writable');
        }
        $this->cache = $cache;
    }

    public function __toString(): string
    {
        if (!$this->stream) {
            return '';
        }
        if ($this->cache && $this->finished) {
            $this->cache->rewind();
            return $this->cache->getContents();
        }
        if ($this->isSeekable()) {
            $this->rewind();
        }
        return $this->getContents();
    }

    #[Override] public function close(): void
    {
        if ($this->stream) {
            if ($this->isPipe()) {
                pclose($this->stream);
            } else {
                fclose($this->stream);
            }
        }

        $this->detach();
    }

    #[Override] public function detach()
    {
        $oldResource = $this->stream;
        $this->stream = null;
        $this->meta = null;
        $this->readable = null;
        $this->writable = null;
        $this->seekable = null;
        $this->size = null;
        $this->isPipe = null;

        $this->cache = null;
        $this->finished = false;

        return $oldResource;
    }

    #[Override] public function getSize(): ?int
    {
        if ($this->stream && !$this->size) {
            $stats = fstat($this->stream);

            if ($stats) {
                $this->size = !$this->isPipe() ? $stats['size'] : null;
            }
        }

        return $this->size;
    }

    #[Override] public function tell(): int
    {
        $position = false;

        if ($this->stream) {
            $position = ftell($this->stream);
        }

        if ($position === false || $this->isPipe()) {
            throw new RuntimeException('Could not get the position of the pointer in stream.');
        }

        return $position;
    }

    #[Override] public function eof(): bool
    {
        return !$this->stream || feof($this->stream);
    }

    #[Override] public function isSeekable(): bool
    {
        if ($this->seekable === null) {
            $this->seekable = false;

            if ($this->stream) {
                $this->seekable = !$this->isPipe() && $this->getMetadata('seekable');
            }
        }

        return $this->seekable;
    }

    #[Override] public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->isSeekable() || $this->stream && fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException('Could not seek in stream.');
        }
    }

    #[Override] public function rewind(): void
    {
        if (!$this->isSeekable() || $this->stream && rewind($this->stream) === false) {
            throw new RuntimeException('Could not rewind stream.');
        }
    }

    #[Override] public function isWritable(): bool
    {
        if ($this->writable === null) {
            $this->writable = false;

            if ($this->stream) {
                $mode = $this->getMetadata('mode');

                if (is_string($mode) && (strstr($mode, 'w') !== false || strstr($mode, '+') !== false)) {
                    $this->writable = true;
                }
            }
        }

        return $this->writable;
    }

    #[Override] public function write(string $string): int
    {
        $written = false;

        if ($this->isWritable() && $this->stream) {
            $written = fwrite($this->stream, $string);
        }

        if ($written !== false) {
            $this->size = null;
            return $written;
        }

        throw new RuntimeException('Could not write to stream.');
    }

    #[Override] public function isReadable(): bool
    {
        if ($this->readable !== null) {
            return $this->readable;
        }

        $this->readable = false;

        if ($this->stream) {
            $mode = $this->getMetadata('mode');

            if (is_string($mode) && (strstr($mode, 'r') !== false || strstr($mode, '+') !== false)) {
                $this->readable = true;
            }
        }

        return $this->readable;
    }

    #[Override] public function read(int $length): string
    {
        $data = false;

        if ($this->isReadable() && $this->stream && $length > 0) {
            $data = fread($this->stream, $length);
        }

        if (is_string($data)) {
            if ($this->cache) {
                $this->cache->write($data);
            }
            if ($this->eof()) {
                $this->finished = true;
            }
            return $data;
        }

        throw new RuntimeException('Could not read from stream.');
    }

    #[Override] public function getContents(): string
    {
        if ($this->cache && $this->finished) {
            $this->cache->rewind();
            return $this->cache->getContents();
        }

        $contents = false;

        if ($this->stream) {
            $contents = stream_get_contents($this->stream);
        }

        if (is_string($contents)) {
            if ($this->cache) {
                $this->cache->write($contents);
            }
            if ($this->eof()) {
                $this->finished = true;
            }
            return $contents;
        }

        throw new RuntimeException('Could not get contents of stream.');
    }

    #[Override] public function getMetadata(?string $key = null)
    {
        if (!$this->stream) {
            return null;
        }

        $this->meta = stream_get_meta_data($this->stream);

        if (!$key) {
            return $this->meta;
        }

        return $this->meta[$key] ?? null;
    }

    private function attach($stream): void
    {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException(__METHOD__ . ' argument must be a valid PHP resource');
        }

        if ($this->stream) {
            $this->detach();
        }

        $this->stream = $stream;
    }

    private function isPipe(): bool
    {
        if ($this->isPipe === null) {
            $this->isPipe = false;

            if ($this->stream) {
                $stats = fstat($this->stream);

                if (is_array($stats)) {
                    $this->isPipe = ($stats['mode'] & self::FSTAT_MODE_S_IFIFO) !== 0;
                }
            }
        }

        return $this->isPipe;
    }
}