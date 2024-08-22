<?php

namespace Framework\Psr\Http\Factory;

use Framework\Psr\Http\Message\ServerRequestInterface;

interface ServerRequestFactoryInterface
{
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface;
}