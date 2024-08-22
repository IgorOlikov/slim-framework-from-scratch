<?php

namespace Framework\Core\Routing;

use Framework\Core\Handlers\Strategies\RequestResponse;
use Framework\Core\Interfaces\CallableResolverInterface;
use Framework\Core\Interfaces\InvocationStrategyInterface;
use Framework\Core\Interfaces\RouteCollectorInterface;
use Framework\Core\Interfaces\RouteGroupInterface;
use Framework\Core\Interfaces\RouteInterface;
use Framework\Core\Interfaces\RouteParserInterface;
use Framework\Psr\Container\ContainerInterface;
use Framework\Psr\Http\Factory\ResponseFactoryInterface;
use Framework\Core\Routing\RouteParser;

use Override;

class RouteCollector implements RouteCollectorInterface
{
    protected RouteParserInterface $routeParser;

    protected CallableResolverInterface $callableResolver;

    protected ?ContainerInterface $container = null;

    protected InvocationStrategyInterface $defaultInvocationStrategy;

    protected string $basePath = '';

    protected ?string $cacheFile = null;

    protected array $routes = [];

    protected array $routesByName = [];

    protected array $routeGroups = [];

    protected int $routeCounter = 0;

    protected ResponseFactoryInterface $responseFactory;


    public function __construct(
        ResponseFactoryInterface $responseFactory,
        CallableResolverInterface $callableResolver,
        ?ContainerInterface $container = null,
        ?InvocationStrategyInterface $defaultInvocationStrategy = null,
        ?RouteParserInterface $routeParser = null,
        ?string $cacheFile = null
    )
    {
        $this->responseFactory = $responseFactory;
        $this->callableResolver = $callableResolver;
        $this->container = $container;
        $this->defaultInvocationStrategy = $defaultInvocationStrategy ?? new RequestResponse();
        $this->routeParser = $routeParser ?? new RouteParser($this);

    }

    #[Override] public function getRouteParser(): RouteParserInterface
    {
        // TODO: Implement getRouteParser() method.
    }

    #[Override] public function getDefaultInvocationStrategy(): InvocationStrategyInterface
    {
        // TODO: Implement getDefaultInvocationStrategy() method.
    }

    #[Override] public function setDefaultInvocationStrategy(InvocationStrategyInterface $strategy): RouteCollectorInterface
    {
        // TODO: Implement setDefaultInvocationStrategy() method.
    }

    #[Override] public function getCacheFile(): ?string
    {
        // TODO: Implement getCacheFile() method.
    }

    #[Override] public function setCacheFile(string $cacheFile): RouteCollectorInterface
    {
        // TODO: Implement setCacheFile() method.
    }

    #[Override] public function getBasePath(): string
    {
        // TODO: Implement getBasePath() method.
    }

    #[Override] public function setBasePath(string $basePath): RouteCollectorInterface
    {
        // TODO: Implement setBasePath() method.
    }

    #[Override] public function getRoutes(): array
    {
        // TODO: Implement getRoutes() method.
    }

    #[Override] public function getNamedRoute(string $name): RouteInterface
    {
        // TODO: Implement getNamedRoute() method.
    }

    #[Override] public function removeNamedRoute(string $name): RouteCollectorInterface
    {
        // TODO: Implement removeNamedRoute() method.
    }

    #[Override] public function lookupRoute(string $identifier): RouteInterface
    {
        // TODO: Implement lookupRoute() method.
    }

    #[Override] public function group(string $pattern, $callable): RouteGroupInterface
    {
        // TODO: Implement group() method.
    }

    #[Override] public function map(array $methods, string $pattern, $handler): RouteInterface
    {
        // TODO: Implement map() method.
    }
}