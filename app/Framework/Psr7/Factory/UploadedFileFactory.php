<?php

namespace Framework\Psr7\Factory;

use Framework\Psr\Http\Factory\UploadedFileFactoryInterface;
use Framework\Psr\Http\Message\StreamInterface;
use Framework\Psr\Http\Message\UploadedFileInterface;
use Framework\Psr7\UploadedFile;
use InvalidArgumentException;

class UploadedFileFactory implements UploadedFileFactoryInterface
{
    public function createUploadedFile(
        StreamInterface                 $stream,
        ?int                            $size = null,
        int                             $error = UPLOAD_ERR_OK,
        ?string                         $clientFilename = null,
        ?string                         $clientMediaType = null
    ): UploadedFileInterface {
        $file = $stream->getMetadata('uri');

        if (!is_string($file) || !$stream->isReadable()) {
            throw new InvalidArgumentException('File is not readable.');
        }

        if ($size === null) {
            $size = $stream->getSize();
        }

        return new UploadedFile($stream, $clientFilename, $clientMediaType, $size, $error);
    }

}