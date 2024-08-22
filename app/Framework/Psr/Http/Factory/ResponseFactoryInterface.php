<?php

namespace Framework\Psr\Http\Factory;

use Framework\Psr\Http\Message\ResponseInterface;

interface ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface;
}