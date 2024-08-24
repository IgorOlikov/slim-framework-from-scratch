<?php

namespace Framework\Core\Interfaces;

use Framework\Core\Routing\RoutingResults;

interface DispatcherInterface
{
    public function dispatch(string $method, string $uri): RoutingResults;

    public function getAllowedMethods(string $uri): array;
}