<?php

namespace Framework\Psr7;

use Framework\Psr\Http\Message\StreamInterface;
use Override;

class NonBufferedBody implements StreamInterface
{

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }

    #[Override] public function close(): void
    {
        // TODO: Implement close() method.
    }

    #[Override] public function detach()
    {
        // TODO: Implement detach() method.
    }

    #[Override] public function getSize(): ?int
    {
        // TODO: Implement getSize() method.
    }

    #[Override] public function tell(): int
    {
        // TODO: Implement tell() method.
    }

    #[Override] public function eof(): bool
    {
        // TODO: Implement eof() method.
    }

    #[Override] public function isSeekable(): bool
    {
        // TODO: Implement isSeekable() method.
    }

    #[Override] public function seek(int $offset, int $whence = SEEK_SET): void
    {
        // TODO: Implement seek() method.
    }

    #[Override] public function rewind(): void
    {
        // TODO: Implement rewind() method.
    }

    #[Override] public function isWritable(): bool
    {
        // TODO: Implement isWritable() method.
    }

    #[Override] public function write(string $string): int
    {
        // TODO: Implement write() method.
    }

    #[Override] public function isReadable(): bool
    {
        // TODO: Implement isReadable() method.
    }

    #[Override] public function read(int $length): string
    {
        // TODO: Implement read() method.
    }

    #[Override] public function getContents(): string
    {
        // TODO: Implement getContents() method.
    }

    #[Override] public function getMetadata(?string $key = null)
    {
        // TODO: Implement getMetadata() method.
    }
}