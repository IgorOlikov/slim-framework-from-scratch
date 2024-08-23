<?php

namespace Framework\Psr7\Factory;

use Framework\Psr\Http\Factory\ServerRequestFactoryInterface;
use Framework\Psr\Http\Factory\StreamFactoryInterface;
use Framework\Psr\Http\Factory\UriFactoryInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\Message\UriInterface;
use Framework\Psr7\Cookies;
use Framework\Psr7\Headers;
use Framework\Psr7\Request;
use Framework\Psr7\Stream;
use Framework\Psr7\UploadedFile;
use InvalidArgumentException;
use Override;

class ServerRequestFactory implements ServerRequestFactoryInterface
{
    protected StreamFactoryInterface $streamFactory;

    protected UriFactoryInterface $uriFactory;

    public function __construct(?StreamFactoryInterface $streamFactory = null, ?UriFactoryInterface $uriFactory = null)
    {
        $this->streamFactory = $streamFactory ?? new StreamFactory();
        $this->uriFactory = $uriFactory ?? new UriFactory();
    }

    #[Override] public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        if (is_string($uri)) {
            $uri = $this->uriFactory->createUri($uri);
        }

        if (!$uri instanceof UriInterface) {
            throw new InvalidArgumentException('URI must either be string or instance of ' . UriInterface::class);
        }

        $body = $this->streamFactory->createStream();
        $headers = new Headers();
        $cookies = [];

        if (!empty($serverParams)) {
            $headers = Headers::createFromGlobals();
            $cookies = Cookies::parseHeader($headers->getHeader('Cookie', []));
        }

        return new Request($method, $uri, $headers, $cookies, $serverParams, $body);
    }

    public static function createFromGlobals(): Request
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = (new UriFactory())->createFromGlobals($_SERVER);

        $headers = Headers::createFromGlobals();
        $cookies = Cookies::parseHeader($headers->getHeader('Cookie', []));

        // Cache the php://input stream as it cannot be re-read
        $cacheResource = fopen('php://temp', 'wb+');
        $cache = $cacheResource ? new Stream($cacheResource) : null;

        $body = (new StreamFactory())->createStreamFromFile('php://input', 'r', $cache);
        $uploadedFiles = UploadedFile::createFromGlobals($_SERVER);

        $request = new Request($method, $uri, $headers, $cookies, $_SERVER, $body, $uploadedFiles);
        $contentTypes = $request->getHeader('Content-Type');

        $parsedContentType = '';
        foreach ($contentTypes as $contentType) {
            $fragments = explode(';', $contentType);
            $parsedContentType = current($fragments);
        }

        $contentTypesWithParsedBodies = ['application/x-www-form-urlencoded', 'multipart/form-data'];
        if ($method === 'POST' && in_array($parsedContentType, $contentTypesWithParsedBodies)) {
            return $request->withParsedBody($_POST);
        }

        return $request;
    }
}