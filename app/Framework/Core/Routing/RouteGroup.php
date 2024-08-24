<?php

namespace Framework\Core\Routing;

use Framework\Core\Interfaces\AdvancedCallableResolverInterface;
use Framework\Core\Interfaces\CallableResolverInterface;
use Framework\Core\Interfaces\RouteCollectorProxyInterface;
use Framework\Core\Interfaces\RouteGroupInterface;
use Framework\Core\MiddlewareDispatcher;
use Framework\Psr\Http\ServerMiddleware\MiddlewareInterface;

class RouteGroup implements RouteGroupInterface
{
    /**
     * @var callable|string
     */
    protected $callable;

    protected CallableResolverInterface $callableResolver;


    protected RouteCollectorProxyInterface $routeCollectorProxy;


    protected array $middleware = [];

    protected string $pattern;


    public function __construct(
        string $pattern,
               $callable,
        CallableResolverInterface $callableResolver,
        RouteCollectorProxyInterface $routeCollectorProxy
    ) {
        $this->pattern = $pattern;
        $this->callable = $callable;
        $this->callableResolver = $callableResolver;
        $this->routeCollectorProxy = $routeCollectorProxy;
    }


    public function collectRoutes(): RouteGroupInterface
    {
        if ($this->callableResolver instanceof AdvancedCallableResolverInterface) {
            $callable = $this->callableResolver->resolveRoute($this->callable);
        } else {
            $callable = $this->callableResolver->resolve($this->callable);
        }
        $callable($this->routeCollectorProxy);
        return $this;
    }


    public function add($middleware): RouteGroupInterface
    {
        $this->middleware[] = $middleware;
        return $this;
    }


    public function addMiddleware(MiddlewareInterface $middleware): RouteGroupInterface
    {
        $this->middleware[] = $middleware;
        return $this;
    }


    public function appendMiddlewareToDispatcher(MiddlewareDispatcher $dispatcher): RouteGroupInterface
    {
        foreach ($this->middleware as $middleware) {
            $dispatcher->add($middleware);
        }

        return $this;
    }


    public function getPattern(): string
    {
        return $this->pattern;
    }
}