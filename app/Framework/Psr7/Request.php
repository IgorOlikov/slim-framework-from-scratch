<?php

namespace Framework\Psr7;

use Framework\Psr\Http\Message\RequestInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\Message\UriInterface;
use Override;

class Request extends Message implements ServerRequestInterface
{

    #[Override] public function getRequestTarget(): string
    {
        // TODO: Implement getRequestTarget() method.
    }

    #[Override] public function withRequestTarget(string $requestTarget): RequestInterface
    {
        // TODO: Implement withRequestTarget() method.
    }

    #[Override] public function getMethod(): string
    {
        // TODO: Implement getMethod() method.
    }

    #[Override] public function withMethod(string $method): RequestInterface
    {
        // TODO: Implement withMethod() method.
    }

    #[Override] public function getUri(): UriInterface
    {
        // TODO: Implement getUri() method.
    }

    #[Override] public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        // TODO: Implement withUri() method.
    }

    #[Override] public function getServerParams(): array
    {
        // TODO: Implement getServerParams() method.
    }

    #[Override] public function getCookieParams(): array
    {
        // TODO: Implement getCookieParams() method.
    }

    #[Override] public function withCookieParams(array $cookies): ServerRequestInterface
    {
        // TODO: Implement withCookieParams() method.
    }

    #[Override] public function getQueryParams(): array
    {
        // TODO: Implement getQueryParams() method.
    }

    #[Override] public function withQueryParams(array $query): ServerRequestInterface
    {
        // TODO: Implement withQueryParams() method.
    }

    #[Override] public function getUploadedFiles(): array
    {
        // TODO: Implement getUploadedFiles() method.
    }

    #[Override] public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        // TODO: Implement withUploadedFiles() method.
    }

    #[Override] public function getParsedBody()
    {
        // TODO: Implement getParsedBody() method.
    }

    #[Override] public function withParsedBody($data): ServerRequestInterface
    {
        // TODO: Implement withParsedBody() method.
    }

    #[Override] public function getAttributes(): array
    {
        // TODO: Implement getAttributes() method.
    }

    #[Override] public function getAttribute(string $name, $default = null)
    {
        // TODO: Implement getAttribute() method.
    }

    #[Override] public function withAttribute(string $name, $value): ServerRequestInterface
    {
        // TODO: Implement withAttribute() method.
    }

    #[Override] public function withoutAttribute(string $name): ServerRequestInterface
    {
        // TODO: Implement withoutAttribute() method.
    }
}