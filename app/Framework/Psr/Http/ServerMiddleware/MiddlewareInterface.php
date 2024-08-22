<?php

namespace Framework\Psr\Http\ServerMiddleware;

use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\ServerHandler\RequestHandlerInterface;

interface MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}