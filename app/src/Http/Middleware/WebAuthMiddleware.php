<?php

namespace App\Http\Middleware;

use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\ServerHandler\RequestHandlerInterface;
use Framework\Psr\Http\ServerMiddleware\MiddlewareInterface;

class WebAuthMiddleware implements MiddlewareInterface
{

    #[\Override] public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: Implement process() method.
    }
}