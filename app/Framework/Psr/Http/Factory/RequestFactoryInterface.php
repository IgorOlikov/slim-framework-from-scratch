<?php

namespace Framework\Psr\Http\Factory;

use Framework\Psr\Http\Message\RequestInterface;

interface RequestFactoryInterface
{
    public function createRequest(string $method, $uri): RequestInterface;
}