<?php

namespace Framework\Core\Routing;

use Framework\Core\Interfaces\RouteCollectorInterface;
use Framework\Core\Interfaces\RouteParserInterface;
use Framework\Psr\Http\Message\UriInterface;
use Framework\Router\RouteParser\Std;
use Override;

class RouteParser implements RouteParserInterface
{
    private RouteCollectorInterface $routeCollector;

    private Std $routeParser;

    public function __construct(RouteCollectorInterface $routeCollector)
    {
        $this->routeCollector = $routeCollector;
        $this->routeParser = new Std();
    }


    #[Override] public function relativeUrlFor(string $routeName, array $data = [], array $queryParams = []): string
    {
        // TODO: Implement relativeUrlFor() method.
    }

    #[Override] public function urlFor(string $routeName, array $data = [], array $queryParams = []): string
    {
        // TODO: Implement urlFor() method.
    }

    #[Override] public function fullUrlFor(UriInterface $uri, string $routeName, array $data = [], array $queryParams = []): string
    {
        // TODO: Implement fullUrlFor() method.
    }
}