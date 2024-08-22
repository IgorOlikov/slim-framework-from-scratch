<?php

namespace Framework\Core\Routing;

use Framework\Core\Interfaces\DispatcherInterface;
use Framework\Core\Interfaces\RouteCollectorInterface;
use Framework\Core\Interfaces\RoutingResults;
use Override;

class Dispatcher implements DispatcherInterface
{
    private RouteCollectorInterface $routeCollector;

    private ?RouteDispatcher $dispatcher = null;

    public function __construct(RouteCollectorInterface $routeCollector)
    {
        $this->routeCollector = $routeCollector;
    }


    #[Override] public function dispatch(string $method, string $uri): RoutingResults
    {
        // TODO: Implement dispatch() method.
    }

    #[Override] public function getAllowedMethods(string $uri): array
    {
        // TODO: Implement getAllowedMethods() method.
    }
}