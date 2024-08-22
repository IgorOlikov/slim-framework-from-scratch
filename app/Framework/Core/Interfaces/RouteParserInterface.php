<?php

namespace Framework\Core\Interfaces;

use Framework\Psr\Http\Message\UriInterface;

interface RouteParserInterface
{
    public function relativeUrlFor(string $routeName, array $data = [], array $queryParams = []): string;

    public function urlFor(string $routeName, array $data = [], array $queryParams = []): string;

    public function fullUrlFor(UriInterface $uri, string $routeName, array $data = [], array $queryParams = []): string;
}