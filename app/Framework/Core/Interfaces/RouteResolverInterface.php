<?php

namespace Framework\Core\Interfaces;

use Framework\Core\Routing\RoutingResults;

interface RouteResolverInterface
{
    public function computeRoutingResults(string $uri, string $method): RoutingResults;

    public function resolveRoute(string $identifier): RouteInterface;
}