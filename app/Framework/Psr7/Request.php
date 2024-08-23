<?php

namespace Framework\Psr7;

use Framework\Psr\Http\Message\RequestInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\Message\StreamInterface;
use Framework\Psr\Http\Message\UploadedFileInterface;
use Framework\Psr\Http\Message\UriInterface;
use Framework\Psr7\Interfaces\HeadersInterface;
use InvalidArgumentException;
use Override;

class Request extends Message implements ServerRequestInterface
{
    protected string $method;

    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @var string
     */
    protected $requestTarget;

    /**
     * @var ?array
     */
    protected ?array $queryParams;

    protected array $cookies;

    protected array $serverParams;

    protected array $attributes;

    /**
     * @var null|array|object
     */
    protected $parsedBody;

    /**
     * @var UploadedFileInterface[]
     */
    protected array $uploadedFiles;

    public function __construct(
        $method,
        UriInterface $uri,
        HeadersInterface $headers,
        array $cookies,
        array $serverParams,
        StreamInterface $body,
        array $uploadedFiles = []
    )
    {
        $this->method = $this->filterMethod($method);
        $this->uri = $uri;
        $this->headers = $headers;
        $this->cookies = $cookies;
        $this->serverParams = $serverParams;
        $this->attributes = [];
        $this->body = $body;
        $this->uploadedFiles = $uploadedFiles;

        if (isset($serverParams['SERVER_PROTOCOL'])) {
            $this->protocolVersion = str_replace('HTTP/', '', $serverParams['SERVER_PROTOCOL']);
        }

        if (!$this->headers->hasHeader('Host') || $this->uri->getHost() !== '') {
            $this->headers->setHeader('Host', $this->uri->getHost());
        }
    }

    public function __clone()
    {
        $this->headers = clone $this->headers;
        $this->body = clone $this->body;
    }

    protected function filterMethod($method): string
    {
        /** @var mixed $method */
        if (!is_string($method)) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported HTTP method; must be a string, received %s',
                (is_object($method) ? get_class($method) : gettype($method))
            ));
        }

        if (preg_match("/^[!#$%&'*+.^_`|~0-9a-z-]+$/i", $method) !== 1) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported HTTP method "%s" provided',
                $method
            ));
        }

        return $method;
    }

    #[Override] public function getRequestTarget(): string
    {
        if ($this->requestTarget) {
            return $this->requestTarget;
        }

        if ($this->uri === null) {
            return '/';
        }

        $path = $this->uri->getPath();
        $path = '/' . ltrim($path, '/');

        $query = $this->uri->getQuery();
        if ($query) {
            $path .= '?' . $query;
        }

        return $path;
    }

    #[Override] public function withRequestTarget($requestTarget): RequestInterface
    {
        if (!is_string($requestTarget) || preg_match('#\s#', $requestTarget)) {
            throw new InvalidArgumentException(
                'Invalid request target provided; must be a string and cannot contain whitespace'
            );
        }

        $clone = clone $this;
        $clone->requestTarget = $requestTarget;

        return $clone;
    }

    #[Override] public function getMethod(): string
    {
        return $this->method;
    }

    #[Override] public function withMethod(string $method): RequestInterface
    {
        $method = $this->filterMethod($method);
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    #[Override] public function getUri(): UriInterface
    {
        return $this->uri;
    }

    #[Override] public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $clone = clone $this;
        $clone->uri = $uri;

        if (!$preserveHost && $uri->getHost() !== '') {
            $clone->headers->setHeader('Host', $uri->getHost());
            return $clone;
        }

        if (($uri->getHost() !== '' && !$this->hasHeader('Host') || $this->getHeaderLine('Host') === '')) {
            $clone->headers->setHeader('Host', $uri->getHost());
            return $clone;
        }

        return $clone;
    }

    #[Override] public function getServerParams(): array
    {
        return $this->serverParams;
    }

    #[Override] public function getCookieParams(): array
    {
        return $this->cookies;
    }

    #[Override] public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->cookies = $cookies;

        return $clone;
    }

    #[Override] public function getQueryParams(): array
    {
        if (is_array($this->queryParams)) {
            return $this->queryParams;
        }

        if ($this->uri === null) {
            return [];
        }

        // Decode URL data
        parse_str($this->uri->getQuery(), $this->queryParams);

        return is_array($this->queryParams) ? $this->queryParams : [];
    }

    #[Override] public function withQueryParams(array $query): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->queryParams = $query;

        return $clone;
    }

    #[Override] public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    #[Override] public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->uploadedFiles = $uploadedFiles;

        return $clone;
    }

    #[Override] public function getParsedBody()
    {
        return $this->parsedBody;
    }

    #[Override] public function withParsedBody($data): ServerRequestInterface
    {
        /** @var mixed $data */
        if (!is_null($data) && !is_object($data) && !is_array($data)) {
            throw new InvalidArgumentException('Parsed body value must be an array, an object, or null');
        }

        $clone = clone $this;
        $clone->parsedBody = $data;

        return $clone;
    }

    #[Override] public function getAttributes(): array
    {
        return $this->attributes;
    }

    #[Override] public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    #[Override] public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    #[Override] public function withoutAttribute(string $name): ServerRequestInterface
    {
        $clone = clone $this;

        unset($clone->attributes[$name]);

        return $clone;
    }
}