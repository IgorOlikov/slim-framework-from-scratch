<?php

namespace Framework\Core\Interfaces;

use Framework\Psr\Http\ServerHandler\RequestHandlerInterface;
use Framework\Psr\Http\ServerMiddleware\MiddlewareInterface;

interface MiddlewareDispatcherInterface extends RequestHandlerInterface
{
    public function add($middleware): self;

    public function addMiddleware(MiddlewareInterface $middleware): self;

    public function seedMiddlewareStack(RequestHandlerInterface $kernel): void;

}