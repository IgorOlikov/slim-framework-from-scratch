<?php

namespace Framework\Router;

Interface Dispatcher
{
    public const int NOT_FOUND = 0;
    public const int FOUND = 1;
    public const int METHOD_NOT_ALLOWED = 2;

    public function dispatch(string $httpMethod, string $uri): Matched|NotMatched|MethodNotAllowed;
}