<?php

namespace Framework\FastRouter;

class RouteCollector implements ConfigureRoutes
{
    protected string $currentGroupPrefix = '';

    private array $namedRoutes = [];

    public function __construct(
        protected readonly RouteParser $routeParser,
        protected readonly DataGenerator $dataGenerator,
    ) {
    }

    public function addRoute(string|array $httpMethod, string $route, mixed $handler, array $extraParameters = []): void
    {
        $route = $this->currentGroupPrefix . $route;
        $parsedRoutes = $this->routeParser->parse($route);

        $extraParameters = [self::ROUTE_REGEX => $route] + $extraParameters;

        foreach ((array) $httpMethod as $method) {
            foreach ($parsedRoutes as $parsedRoute) {
                $this->dataGenerator->addRoute($method, $parsedRoute, $handler, $extraParameters);
            }
        }

        if (array_key_exists(self::ROUTE_NAME, $extraParameters)) {
            $this->registerNamedRoute($extraParameters[self::ROUTE_NAME], $parsedRoutes);
        }
    }

    private function registerNamedRoute(mixed $name, array $parsedRoutes): void
    {
        if (! is_string($name) || $name === '') {
            throw BadRouteException::invalidRouteName($name);
        }

        if (array_key_exists($name, $this->namedRoutes)) {
            throw BadRouteException::namedRouteAlreadyDefined($name);
        }

        $this->namedRoutes[$name] = array_reverse($parsedRoutes);
    }

    public function addGroup(string $prefix, callable $callback): void
    {
        $previousGroupPrefix = $this->currentGroupPrefix;
        $this->currentGroupPrefix = $previousGroupPrefix . $prefix;
        $callback($this);
        $this->currentGroupPrefix = $previousGroupPrefix;
    }

    public function any(string $route, mixed $handler, array $extraParameters = []): void
    {
        $this->addRoute('*', $route, $handler, $extraParameters);
    }

    public function get(string $route, mixed $handler, array $extraParameters = []): void
    {
        $this->addRoute('GET', $route, $handler, $extraParameters);
    }


    public function post(string $route, mixed $handler, array $extraParameters = []): void
    {
        $this->addRoute('POST', $route, $handler, $extraParameters);
    }

    public function put(string $route, mixed $handler, array $extraParameters = []): void
    {
        $this->addRoute('PUT', $route, $handler, $extraParameters);
    }

    public function delete(string $route, mixed $handler, array $extraParameters = []): void
    {
        $this->addRoute('DELETE', $route, $handler, $extraParameters);
    }

    public function patch(string $route, mixed $handler, array $extraParameters = []): void
    {
        $this->addRoute('PATCH', $route, $handler, $extraParameters);
    }

    public function head(string $route, mixed $handler, array $extraParameters = []): void
    {
        $this->addRoute('HEAD', $route, $handler, $extraParameters);
    }


    public function options(string $route, mixed $handler, array $extraParameters = []): void
    {
        $this->addRoute('OPTIONS', $route, $handler, $extraParameters);
    }

    public function processedRoutes(): array
    {
        $data =  $this->dataGenerator->getData();
        $data[] = $this->namedRoutes;

        return $data;
    }


    public function getData(): array
    {
        return $this->processedRoutes();
    }
}