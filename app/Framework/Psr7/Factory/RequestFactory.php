<?php

namespace Framework\Psr7\Factory;

use Framework\Psr\Http\Factory\RequestFactoryInterface;
use Framework\Psr\Http\Factory\StreamFactoryInterface;
use Framework\Psr\Http\Factory\UriFactoryInterface;
use Framework\Psr\Http\Message\RequestInterface;
use Framework\Psr\Http\Message\UriInterface;
use Framework\Psr7\Headers;
use Framework\Psr7\Request;
use InvalidArgumentException;
use Override;

class RequestFactory implements RequestFactoryInterface
{
    protected  StreamFactoryInterface $streamFactory;

    protected UriFactoryInterface $uriFactory;

    public function __construct(?StreamFactoryInterface $streamFactory = null, ?UriFactoryInterface $uriFactory = null)
    {
        $this->streamFactory = $streamFactory ?? new StreamFactory();
        $this->uriFactory = $uriFactory ?? new UriFactory();
    }


    #[Override] public function createRequest(string $method, $uri): RequestInterface
    {
        if (is_string($uri)) {
            $uri = $this->uriFactory->createUri($uri);
        }

        if (!$uri instanceof UriInterface) {
            throw new InvalidArgumentException(
                'Parameter 2 of RequestFactory::createRequest() must be a string or a compatible UriInterface.'
            );
        }

        $body = $this->streamFactory->createStream();

        return new Request($method, $uri, new Headers(), [], [], $body);
    }
}