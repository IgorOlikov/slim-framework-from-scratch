<?php

namespace Framework\Psr7Request;

use Framework\Psr\Http\Message\MessageInterface;
use Framework\Psr\Http\Message\StreamInterface;
use Framework\Psr7Request\Interfaces\HeadersInterface;
use Override;

class Message implements MessageInterface
{
    protected string $protocolVersion = '1.1';

    protected static array $validProtocolVersions = [
        '1.0' => true,
        '1.1' => true,
        '2.0' => true,
        '2' => true,
    ];

    protected HeadersInterface $headers;

    protected StreamInterface $body;

    public function __set($name, $value): void
    {
        // Do nothing
    }

    #[Override] public function getProtocolVersion(): string
    {
        // TODO: Implement getProtocolVersion() method.
    }

    #[Override] public function withProtocolVersion(string $version): MessageInterface
    {
        // TODO: Implement withProtocolVersion() method.
    }

    #[Override] public function getHeaders(): array
    {
        // TODO: Implement getHeaders() method.
    }

    #[Override] public function hasHeader(string $name): bool
    {
        // TODO: Implement hasHeader() method.
    }

    #[Override] public function getHeader(string $name): array
    {
        // TODO: Implement getHeader() method.
    }

    #[Override] public function getHeaderLine(string $name): string
    {
        // TODO: Implement getHeaderLine() method.
    }

    #[Override] public function withHeader(string $name, $value): MessageInterface
    {
        // TODO: Implement withHeader() method.
    }

    #[Override] public function withAddedHeader(string $name, $value): MessageInterface
    {
        // TODO: Implement withAddedHeader() method.
    }

    #[Override] public function withoutHeader(string $name): MessageInterface
    {
        // TODO: Implement withoutHeader() method.
    }

    #[Override] public function getBody(): StreamInterface
    {
        // TODO: Implement getBody() method.
    }

    #[Override] public function withBody(StreamInterface $body): MessageInterface
    {
        // TODO: Implement withBody() method.
    }
}