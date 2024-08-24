<?php

namespace Framework\Core\Interfaces;

use Framework\Core\MiddlewareDispatcher;
use Framework\Psr\Http\ServerMiddleware\MiddlewareInterface;

interface RouteGroupInterface
{
    public function collectRoutes(): RouteGroupInterface;

    public function add($middleware): RouteGroupInterface;

    public function addMiddleware(MiddlewareInterface $middleware): RouteGroupInterface;

    public function appendMiddlewareToDispatcher(MiddlewareDispatcher $dispatcher): RouteGroupInterface;

    public function getPattern(): string;
}