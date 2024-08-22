<?php

namespace Framework\Psr7;

use Framework\Psr\Http\Message\StreamInterface;
use Framework\Psr\Http\Message\UploadedFileInterface;
use Override;

class UploadedFile implements UploadedFileInterface
{

    #[Override] public function getStream(): StreamInterface
    {
        // TODO: Implement getStream() method.
    }

    #[Override] public function moveTo(string $targetPath): void
    {
        // TODO: Implement moveTo() method.
    }

    #[Override] public function getSize(): ?int
    {
        // TODO: Implement getSize() method.
    }

    #[Override] public function getError(): int
    {
        // TODO: Implement getError() method.
    }

    #[Override] public function getClientFilename(): ?string
    {
        // TODO: Implement getClientFilename() method.
    }

    #[Override] public function getClientMediaType(): ?string
    {
        // TODO: Implement getClientMediaType() method.
    }
}