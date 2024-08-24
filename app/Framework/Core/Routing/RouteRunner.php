<?php

namespace Framework\Core\Routing;

use Framework\Core\Interfaces\RouteCollectorProxyInterface;
use Framework\Core\Interfaces\RouteParserInterface;
use Framework\Core\Interfaces\RouteResolverInterface;
use Framework\Core\Middleware\RoutingMiddleware;
use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\ServerHandler\RequestHandlerInterface;

class RouteRunner implements RequestHandlerInterface
{
    private RouteResolverInterface $routeResolver;

    private RouteParserInterface $routeParser;

    private ?RouteCollectorProxyInterface $routeCollectorProxy;


    public function __construct(
        RouteResolverInterface $routeResolver,
        RouteParserInterface $routeParser,
        ?RouteCollectorProxyInterface $routeCollectorProxy = null
    ) {
        $this->routeResolver = $routeResolver;
        $this->routeParser = $routeParser;
        $this->routeCollectorProxy = $routeCollectorProxy;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // If routing hasn't been done, then do it now so we can dispatch
        if ($request->getAttribute(RouteContext::ROUTING_RESULTS) === null) {
            $routingMiddleware = new RoutingMiddleware($this->routeResolver, $this->routeParser);
            $request = $routingMiddleware->performRouting($request);
        }

        if ($this->routeCollectorProxy !== null) {
            $request = $request->withAttribute(
                RouteContext::BASE_PATH,
                $this->routeCollectorProxy->getBasePath()
            );
        }

        /** @var Route $route */
        $route = $request->getAttribute(RouteContext::ROUTE);

        return $route->run($request);
    }
}