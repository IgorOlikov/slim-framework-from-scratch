<?php

namespace Framework\Core\Routing;

use Framework\Core\Interfaces\CallableResolverInterface;
use Framework\Core\Interfaces\RouteCollectorInterface;
use Framework\Core\Interfaces\RouteCollectorProxyInterface;
use Framework\Core\Interfaces\RouteGroupInterface;
use Framework\Core\Interfaces\RouteInterface;
use Framework\Psr\Container\ContainerInterface;
use Framework\Psr\Http\Factory\ResponseFactoryInterface;
use Override;

class RouteCollectorProxy implements RouteCollectorProxyInterface
{
    protected ResponseFactoryInterface $responseFactory;

    protected CallableResolverInterface $callableResolver;

    protected ?ContainerInterface $container = null;

    protected RouteCollectorInterface $routeCollector;

    protected string $groupPattern;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        CallableResolverInterface $callableResolver,
        ?ContainerInterface $container = null,
        ?RouteCollectorInterface $routeCollector = null,
        string $groupPattern = ''
    )
    {
        $this->responseFactory = $responseFactory;
        $this->callableResolver = $callableResolver;
        $this->container = $container;
        $this->routeCollector = $routeCollector ?? new RouteCollector($responseFactory, $callableResolver, $container);
        $this->groupPattern = $groupPattern;

    }


    #[Override] public function getResponseFactory(): ResponseFactoryInterface
    {
        // TODO: Implement getResponseFactory() method.
    }

    #[Override] public function getCallableResolver(): CallableResolverInterface
    {
        // TODO: Implement getCallableResolver() method.
    }

    #[Override] public function getContainer(): ?ContainerInterface
    {
        // TODO: Implement getContainer() method.
    }

    #[Override] public function getRouteCollector(): RouteCollectorInterface
    {
        // TODO: Implement getRouteCollector() method.
    }

    #[Override] public function getBasePath(): string
    {
        // TODO: Implement getBasePath() method.
    }

    #[Override] public function setBasePath(string $basePath): RouteCollectorProxyInterface
    {
        // TODO: Implement setBasePath() method.
    }

    #[Override] public function get(string $pattern, $callable): RouteInterface
    {
        // TODO: Implement get() method.
    }

    #[Override] public function post(string $pattern, $callable): RouteInterface
    {
        // TODO: Implement post() method.
    }

    #[Override] public function put(string $pattern, $callable): RouteInterface
    {
        // TODO: Implement put() method.
    }

    #[Override] public function patch(string $pattern, $callable): RouteInterface
    {
        // TODO: Implement patch() method.
    }

    #[Override] public function delete(string $pattern, $callable): RouteInterface
    {
        // TODO: Implement delete() method.
    }

    #[Override] public function options(string $pattern, $callable): RouteInterface
    {
        // TODO: Implement options() method.
    }

    #[Override] public function any(string $pattern, $callable): RouteInterface
    {
        // TODO: Implement any() method.
    }

    #[Override] public function map(array $methods, string $pattern, $callable): RouteInterface
    {
        // TODO: Implement map() method.
    }

    #[Override] public function group(string $pattern, $callable): RouteGroupInterface
    {
        // TODO: Implement group() method.
    }

    #[Override] public function redirect(string $from, $to, int $status = 302): RouteInterface
    {
        // TODO: Implement redirect() method.
    }
}