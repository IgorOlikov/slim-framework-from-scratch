<?php

namespace Framework\Core\Routing;

use Framework\Core\Interfaces\DispatcherInterface;
use Framework\Core\Interfaces\RouteCollectorInterface;
use Framework\Core\Interfaces\RouteInterface;
use Framework\Core\Interfaces\RouteResolverInterface;
use Framework\Core\Interfaces\RoutingResults;
use Override;

class RouteResolver implements RouteResolverInterface
{
    protected RouteCollectorInterface $routeCollector;

    private DispatcherInterface $dispatcher;

    public function __construct(RouteCollectorInterface $routeCollector, ?DispatcherInterface $dispatcher = null)
    {
        $this->routeCollector = $routeCollector;
        $this->dispatcher = $dispatcher ?? new Dispatcher($routeCollector);

    }


    #[Override] public function computeRoutingResults(string $uri, string $method): RoutingResults
    {
        // TODO: Implement computeRoutingResults() method.
    }

    #[Override] public function resolveRoute(string $identifier): RouteInterface
    {
        // TODO: Implement resolveRoute() method.
    }
}