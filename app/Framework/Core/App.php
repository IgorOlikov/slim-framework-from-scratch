<?php

namespace Framework\Core;

use Framework\Core\Factory\ServerRequestCreatorFactory;
use Framework\Core\Interfaces\CallableResolverInterface;
use Framework\Core\Interfaces\MiddlewareDispatcherInterface;
use Framework\Core\Interfaces\RouteCollectorInterface;
use Framework\Core\Interfaces\RouteResolverInterface;
use Framework\Core\Middleware\BodyParsingMiddleware;
use Framework\Core\Middleware\ErrorMiddleware;
use Framework\Core\Middleware\RoutingMiddleware;
use Framework\Core\Routing\RouteCollectorProxy;
use Framework\Core\Routing\RouteResolver;
use Framework\Core\Routing\RouteRunner;
use Framework\Psr\Container\ContainerInterface;
use Framework\Psr\Http\Factory\ResponseFactoryInterface;
use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\ServerHandler\RequestHandlerInterface;
use Framework\Psr\Http\ServerMiddleware\MiddlewareInterface;
use Framework\Psr\Log\LoggerInterface;


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

    /**
     * @return RouteResolverInterface
     */
    public function getRouteResolver(): RouteResolverInterface
    {
        return $this->routeResolver;
    }

    /**
     * @return MiddlewareDispatcherInterface
     */
    public function getMiddlewareDispatcher(): MiddlewareDispatcherInterface
    {
        return $this->middlewareDispatcher;
    }

    public function add($middleware): self
    {
        $this->middlewareDispatcher->add($middleware);
        return $this;
    }

    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middlewareDispatcher->addMiddleware($middleware);
        return $this;
    }

    public function addRoutingMiddleware(): RoutingMiddleware
    {
        $routingMiddleware = new RoutingMiddleware(
            $this->getRouteResolver(),
            $this->getRouteCollector()->getRouteParser()
        );
        $this->add($routingMiddleware);
        return $routingMiddleware;
    }

    /**
     * Add the Slim built-in error middleware to the app middleware stack
     *
     * @param bool                 $displayErrorDetails
     * @param bool                 $logErrors
     * @param bool                 $logErrorDetails
     * @param LoggerInterface|null $logger
     *
     * @return ErrorMiddleware
     */
    public function addErrorMiddleware(
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
        ?LoggerInterface $logger = null
    ): ErrorMiddleware {
        $errorMiddleware = new ErrorMiddleware(
            $this->getCallableResolver(),
            $this->getResponseFactory(),
            $displayErrorDetails,
            $logErrors,
            $logErrorDetails,
            $logger
        );
        $this->add($errorMiddleware);
        return $errorMiddleware;
    }


    public function addBodyParsingMiddleware(array $bodyParsers = []): BodyParsingMiddleware
    {
        $bodyParsingMiddleware = new BodyParsingMiddleware($bodyParsers);
        $this->add($bodyParsingMiddleware);
        return $bodyParsingMiddleware;
    }



    public function run(?ServerRequestInterface $request = null): void
    {
        if (!$request) {
            $serverRequestCreator = ServerRequestCreatorFactory::create();
            $request = $serverRequestCreator->createServerRequestFromGlobals();
        }

        $response = $this->handle($request);

        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);

    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->middlewareDispatcher->handle($request);

        $method = strtoupper($request->getMethod());
        if ($method === 'HEAD') {
            $emptyBody = $this->responseFactory->createResponse()->getBody();
            return $response->withBody($emptyBody);
        }

        return $response;
    }
}