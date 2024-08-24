<?php

namespace Framework\Core\Routing;

use Framework\Core\Handlers\Strategies\RequestResponse;
use Framework\Core\Interfaces\CallableResolverInterface;
use Framework\Core\Interfaces\InvocationStrategyInterface;
use Framework\Core\Interfaces\RouteCollectorInterface;
use Framework\Core\Interfaces\RouteCollectorProxyInterface;
use Framework\Core\Interfaces\RouteGroupInterface;
use Framework\Core\Interfaces\RouteInterface;
use Framework\Core\Interfaces\RouteParserInterface;
use Framework\Psr\Container\ContainerInterface;
use Framework\Psr\Http\Factory\ResponseFactoryInterface;


use Override;
use RuntimeException;

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

        if ($cacheFile) {
            $this->setCacheFile($cacheFile);
        }
    }

    #[Override] public function getRouteParser(): RouteParserInterface
    {
        return $this->routeParser;
    }

    #[Override] public function getDefaultInvocationStrategy(): InvocationStrategyInterface
    {
        return $this->defaultInvocationStrategy;
    }

    #[Override] public function setDefaultInvocationStrategy(InvocationStrategyInterface $strategy): RouteCollectorInterface
    {
        $this->defaultInvocationStrategy = $strategy;
        return $this;
    }

    #[Override] public function getCacheFile(): ?string
    {
        return $this->cacheFile;
    }

    #[Override] public function setCacheFile(string $cacheFile): RouteCollectorInterface
    {
        if (file_exists($cacheFile) && !is_readable($cacheFile)) {
            throw new RuntimeException(
                sprintf('Route collector cache file `%s` is not readable', $cacheFile)
            );
        }

        if (!file_exists($cacheFile) && !is_writable(dirname($cacheFile))) {
            throw new RuntimeException(
                sprintf('Route collector cache file directory `%s` is not writable', dirname($cacheFile))
            );
        }

        $this->cacheFile = $cacheFile;
        return $this;
    }

    #[Override] public function getBasePath(): string
    {
        return $this->basePath;
    }

    #[Override] public function setBasePath(string $basePath): RouteCollectorInterface
    {
        $this->basePath = $basePath;

        return $this;
    }

    #[Override] public function getRoutes(): array
    {
        return $this->routes;
    }

    #[Override] public function getNamedRoute(string $name): RouteInterface
    {
        if (isset($this->routesByName[$name])) {
            $route = $this->routesByName[$name];
            if ($route->getName() === $name) {
                return $route;
            }

            unset($this->routesByName[$name]);
        }

        foreach ($this->routes as $route) {
            if ($name === $route->getName()) {
                $this->routesByName[$name] = $route;
                return $route;
            }
        }

        throw new RuntimeException('Named route does not exist for name: ' . $name);
    }

    #[Override] public function removeNamedRoute(string $name): RouteCollectorInterface
    {
        $route = $this->getNamedRoute($name);

        unset($this->routesByName[$route->getName()], $this->routes[$route->getIdentifier()]);
        return $this;
    }

    #[Override] public function lookupRoute(string $identifier): RouteInterface
    {
        if (!isset($this->routes[$identifier])) {
            throw new RuntimeException('Route not found, looks like your route cache is stale.');
        }
        return $this->routes[$identifier];
    }

    #[Override] public function group(string $pattern, $callable): RouteGroupInterface
    {
        $routeGroup = $this->createGroup($pattern, $callable);
        $this->routeGroups[] = $routeGroup;

        $routeGroup->collectRoutes();
        array_pop($this->routeGroups);

        return $routeGroup;
    }

    #[Override] public function map(array $methods, string $pattern, $handler): RouteInterface
    {
        $route = $this->createRoute($methods, $pattern, $handler);
        $this->routes[$route->getIdentifier()] = $route;

        $routeName = $route->getName();
        if ($routeName !== null && !isset($this->routesByName[$routeName])) {
            $this->routesByName[$routeName] = $route;
        }

        $this->routeCounter++;

        return $route;
    }

    private function createGroup(string $pattern, $callable): RouteGroupInterface
    {
        $routeCollectorProxy = $this->createProxy($pattern);
        return new RouteGroup($pattern, $callable, $this->callableResolver, $routeCollectorProxy);
    }

    protected function createProxy(string $pattern): RouteCollectorProxyInterface
    {
        return new RouteCollectorProxy(
            $this->responseFactory,
            $this->callableResolver,
            $this->container,
            $this,
            $pattern
        );
    }

    protected function createRoute(array $methods, string $pattern, $callable): RouteInterface
    {
        return new Route(
            $methods,
            $pattern,
            $callable,
            $this->responseFactory,
            $this->callableResolver,
            $this->container,
            $this->defaultInvocationStrategy,
            $this->routeGroups,
            $this->routeCounter
        );
    }


}