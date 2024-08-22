<?php

namespace Framework\Psr\Http\Factory;

use Framework\Psr\Http\Message\StreamInterface;
use Framework\Psr\Http\Message\UploadedFileInterface;

interface UploadedFileFactoryInterface
{
    public function createUploadedFile(
        StreamInterface $stream,
        ?int $size = null,
        int $error = \UPLOAD_ERR_OK,
        ?string $clientFilename = null,
        ?string $clientMediaType = null
    ): UploadedFileInterface;
}