<?php

namespace Framework\Psr7;

use Framework\Psr\Http\Message\MessageInterface;
use Framework\Psr\Http\Message\StreamInterface;
use Framework\Psr7\Interfaces\HeadersInterface;
use InvalidArgumentException;
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
        return $this->protocolVersion;
    }

    #[Override] public function withProtocolVersion(string $version): MessageInterface
    {
        if (!isset(self::$validProtocolVersions[$version])) {
            throw new InvalidArgumentException(
                'Invalid HTTP version. Must be one of: '
                . implode(', ', array_keys(self::$validProtocolVersions))
            );
        }

        $clone = clone $this;
        $clone->protocolVersion = $version;

        return $clone;
    }

    #[Override] public function getHeaders(): array
    {
        return $this->headers->getHeaders(true);
    }

    #[Override] public function hasHeader(string $name): bool
    {
        return $this->headers->hasHeader($name);
    }

    #[Override] public function getHeader(string $name): array
    {
        return $this->headers->getHeader($name);
    }

    #[Override] public function getHeaderLine(string $name): string
    {
        $values = $this->headers->getHeader($name);
        return implode(',', $values);
    }

    #[Override] public function withHeader(string $name, $value): MessageInterface
    {
        $clone = clone $this;
        $clone->headers->setHeader($name, $value);

        if ($this instanceof Response && $this->body instanceof NonBufferedBody) {
            header(sprintf('%s: %s', $name, $clone->getHeaderLine($name)));
        }

        return $clone;
    }

    #[Override] public function withAddedHeader(string $name, $value): MessageInterface
    {
        $clone = clone $this;
        $clone->headers->addHeader($name, $value);

        if ($this instanceof Response && $this->body instanceof NonBufferedBody) {
            header(sprintf('%s: %s', $name, $clone->getHeaderLine($name)));
        }

        return $clone;
    }

    #[Override] public function withoutHeader(string $name): MessageInterface
    {
        $clone = clone $this;
        $clone->headers->removeHeader($name);

        if ($this instanceof Response && $this->body instanceof NonBufferedBody) {
            header_remove($name);
        }

        return $clone;
    }

    #[Override] public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }
}