<?php

namespace Framework\FastRouter;

interface ConfigureRoutes
{
    public const string ROUTE_NAME = '_name';
    public const string ROUTE_REGEX = '_route';

    /**
     * Registers a new route.
     *
     * The syntax used in the $route string depends on the used route parser.
     *
     * @param string|string[] $httpMethod
     * @param string $route
     * @param mixed $handler
     * @param array $extraParameters
     */
    public function addRoute(string|array $httpMethod, string $route, mixed $handler, array $extraParameters = []): void;

    /**
     * Create a route group with a common prefix.
     *
     * All routes created by the passed callback will have the given group prefix prepended.
     */
    public function addGroup(string $prefix, callable $callback): void;

    /**
     * Adds a fallback route to the collection
     *
     * This is simply an alias of $this->addRoute('*', $route, $handler)
     *
     * @param string $route
     * @param mixed $handler
     * @param array $extraParameters
     */
    public function any(string $route, mixed $handler, array $extraParameters = []): void;

    /**
     * Adds a GET route to the collection
     *
     * This is simply an alias of $this->addRoute('GET', $route, $handler)
     *
     * @param string $route
     * @param mixed $handler
     * @param array $extraParameters
     */
    public function get(string $route, mixed $handler, array $extraParameters = []): void;

    /**
     * Adds a POST route to the collection
     *
     * This is simply an alias of $this->addRoute('POST', $route, $handler)
     *
     * @param string $route
     * @param mixed $handler
     * @param array $extraParameters
     */
    public function post(string $route, mixed $handler, array $extraParameters = []): void;

    /**
     * Adds a PUT route to the collection
     *
     * This is simply an alias of $this->addRoute('PUT', $route, $handler)
     *
     * @param string $route
     * @param mixed $handler
     * @param array $extraParameters
     */
    public function put(string $route, mixed $handler, array $extraParameters = []): void;

    /**
     * Adds a DELETE route to the collection
     *
     * This is simply an alias of $this->addRoute('DELETE', $route, $handler)
     *
     * @param string $route
     * @param mixed $handler
     * @param array $extraParameters
     */
    public function delete(string $route, mixed $handler, array $extraParameters = []): void;

    /**
     * Adds a PATCH route to the collection
     *
     * This is simply an alias of $this->addRoute('PATCH', $route, $handler)
     *
     * @param string $route
     * @param mixed $handler
     * @param array $extraParameters
     */
    public function patch(string $route, mixed $handler, array $extraParameters = []): void;

    /**
     * Adds a HEAD route to the collection
     *
     * This is simply an alias of $this->addRoute('HEAD', $route, $handler)
     *
     * @param string $route
     * @param mixed $handler
     * @param array $extraParameters
     */
    public function head(string $route, mixed $handler, array $extraParameters = []): void;

    /**
     * Adds an OPTIONS route to the collection
     *
     * This is simply an alias of $this->addRoute('OPTIONS', $route, $handler)
     *
     * @param string $route
     * @param mixed $handler
     * @param array $extraParameters
     */
    public function options(string $route, mixed $handler, array $extraParameters = []): void;

    /**
     * Returns the processed aggregated route data.
     *
     * @return array
     */
    public function processedRoutes(): array;
}