<?php

namespace Framework\Core\Interfaces;

interface DispatcherInterface
{
    public function dispatch(string $method, string $uri): RoutingResults;

    public function getAllowedMethods(string $uri): array;
}