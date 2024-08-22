<?php

namespace Framework\Psr\Http\Factory;

use Framework\Psr\Http\Message\UriInterface;

interface UriFactoryInterface
{
    public function createUri(string $uri = ''): UriInterface;
}