<?php

namespace Framework\Core\Interfaces;

interface RouteResolverInterface
{
    public function computeRoutingResults(string $uri, string $method): RoutingResults;

    public function resolveRoute(string $identifier): RouteInterface;
}