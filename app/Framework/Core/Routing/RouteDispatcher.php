<?php

namespace Framework\Core\Routing;

use Framework\Core\Interfaces\MiddlewareDispatcherInterface;
use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\ServerHandler\RequestHandlerInterface;
use Framework\Psr\Http\ServerMiddleware\MiddlewareInterface;

class RouteDispatcher implements MiddlewareDispatcherInterface
{

    #[\Override] public function add($middleware): MiddlewareDispatcherInterface
    {
        // TODO: Implement add() method.
    }

    #[\Override] public function addMiddleware(MiddlewareInterface $middleware): MiddlewareDispatcherInterface
    {
        // TODO: Implement addMiddleware() method.
    }

    #[\Override] public function seedMiddlewareStack(RequestHandlerInterface $kernel): void
    {
        // TODO: Implement seedMiddlewareStack() method.
    }

    #[\Override] public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement handle() method.
    }
}