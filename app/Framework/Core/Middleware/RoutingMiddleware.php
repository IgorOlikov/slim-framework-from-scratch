<?php

namespace Framework\Core\Middleware;

use Framework\Core\Interfaces\RouteParserInterface;
use Framework\Core\Interfaces\RouteResolverInterface;
use Framework\Core\Routing\RouteContext;
use Framework\Core\Routing\RoutingResults;
use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\ServerHandler\RequestHandlerInterface;
use Framework\Psr\Http\ServerMiddleware\MiddlewareInterface;
use Override;
use RuntimeException;

class RoutingMiddleware implements MiddlewareInterface
{

    protected RouteResolverInterface $routeResolver;

    protected RouteParserInterface $routeParser;

    public function __construct(RouteResolverInterface $routeResolver, RouteParserInterface $routeParser)
    {
        $this->routeResolver = $routeResolver;
        $this->routeParser = $routeParser;
    }

    #[Override] public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->performRouting($request);

        return $handler->handle($request);
    }

    public function performRouting(ServerRequestInterface $request): ServerRequestInterface
    {
        $request = $request->withAttribute(RouteContext::ROUTE_PARSER, $this->routeParser);

        $routingResults = $this->resolveRoutingResultsFromRequest($request);
        $routeStatus = $routingResults->getRouteStatus();

        $request = $request->withAttribute(RouteContext::ROUTING_RESULTS, $routingResults);

        switch ($routeStatus) {
            case RoutingResults::FOUND:
                $routeArguments = $routingResults->getRouteArguments();
                $routeIdentifier = $routingResults->getRouteIdentifier() ?? '';
                $route = $this->routeResolver
                    ->resolveRoute($routeIdentifier)
                    ->prepare($routeArguments);
                return $request->withAttribute(RouteContext::ROUTE, $route);

            case RoutingResults::NOT_FOUND:
                throw new HttpNotFoundException($request);

            case RoutingResults::METHOD_NOT_ALLOWED:
                $exception = new HttpMethodNotAllowedException($request);
                $exception->setAllowedMethods($routingResults->getAllowedMethods());
                throw $exception;

            default:
                throw new RuntimeException('An unexpected error occurred while performing routing.');
        }

    }

    protected function resolveRoutingResultsFromRequest(ServerRequestInterface $request): RoutingResults
    {
        return $this->routeResolver->computeRoutingResults(
            $request->getUri()->getPath(),
            $request->getMethod()
        );
    }
}