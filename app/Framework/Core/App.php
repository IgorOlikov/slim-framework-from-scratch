<?php

namespace Framework\Core;


use Framework\Core\Interfaces\CallableResolverInterface;
use Framework\Core\Interfaces\MiddlewareDispatcherInterface;
use Framework\Core\Interfaces\RouteCollectorInterface;
use Framework\Core\Interfaces\RouteResolverInterface;
use Framework\Core\Routing\RouteCollectorProxy;
use Framework\Core\Routing\RouteResolver;
use Framework\Core\Routing\RouteRunner;
use Framework\Psr\Container\ContainerInterface;
use Framework\Psr\Http\Factory\ResponseFactoryInterface;
use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\ServerHandler\RequestHandlerInterface;
use Override;

class App extends RouteCollectorProxy implements RequestHandlerInterface
{

    public const string VERSION = '4.12.0';

    protected RouteResolverInterface $routeResolver;

    protected MiddlewareDispatcherInterface $middlewareDispatcher;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        ?ContainerInterface $container = null,
        CallableResolverInterface $callableResolver = null,
        ?RouteCollectorInterface $routeCollector = null,
        ?RouteResolverInterface $routeResolver = null,
        ?MiddlewareDispatcherInterface $middlewareDispatcher = null
    )
    {
        parent::__construct(
            $responseFactory,
                $callableResolver ?? new CallableResolver($container),
            $container,
            $routeCollector
        );

        $this->routeResolver = $routeResolver ?? new RouteResolver($this->routeCollector);
        $routeRunner = new RouteRunner($this->routeResolver, $this->routeCollector->getRouteParser(), $this);

        if (!$middlewareDispatcher) {
            $middlewareDispatcher = new MiddlewareDispatcher($routeRunner, $this->callableResolver, $container);
        } else {
            $middlewareDispatcher->seedMiddlewareStack($routeRunner);
        }

        $this->middlewareDispatcher = $middlewareDispatcher;

    }

    #[Override] public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement handle() method.
    }
}