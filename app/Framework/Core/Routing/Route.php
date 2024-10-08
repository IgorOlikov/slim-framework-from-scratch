<?php

namespace Framework\Core\Routing;

use Framework\Core\Handlers\Strategies\RequestHandler;
use Framework\Core\Handlers\Strategies\RequestResponse;
use Framework\Core\Interfaces\AdvancedCallableResolverInterface;
use Framework\Core\Interfaces\CallableResolverInterface;
use Framework\Core\Interfaces\InvocationStrategyInterface;
use Framework\Core\Interfaces\RequestHandlerInvocationStrategyInterface;
use Framework\Core\Interfaces\RouteGroupInterface;
use Framework\Core\Interfaces\RouteInterface;
use Framework\Core\MiddlewareDispatcher;
use Framework\Psr\Container\ContainerInterface;
use Framework\Psr\Http\Factory\ResponseFactoryInterface;
use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\ServerHandler\RequestHandlerInterface;
use Framework\Psr\Http\ServerMiddleware\MiddlewareInterface;

class Route implements RouteInterface, RequestHandlerInterface
{
    protected array $methods = [];

    protected string $identifier;

    protected ?string $name = null;


    protected array $groups;

    protected InvocationStrategyInterface $invocationStrategy;

    protected array $arguments = [];

    protected array $savedArguments = [];

    protected ?ContainerInterface $container = null;

    protected MiddlewareDispatcher $middlewareDispatcher;


    protected $callable;

    protected CallableResolverInterface $callableResolver;

    protected ResponseFactoryInterface $responseFactory;

    protected string $pattern;

    protected bool $groupMiddlewareAppended = false;

    public function __construct(
        array $methods,
        string $pattern,
        $callable,
        ResponseFactoryInterface $responseFactory,
        CallableResolverInterface $callableResolver,
        ?ContainerInterface $container = null,
        ?InvocationStrategyInterface $invocationStrategy = null,
        array $groups = [],
        int $identifier = 0
    ) {
        $this->methods = $methods;
        $this->pattern = $pattern;
        $this->callable = $callable;
        $this->responseFactory = $responseFactory;
        $this->callableResolver = $callableResolver;
        $this->container = $container;
        $this->invocationStrategy = $invocationStrategy ?? new RequestResponse();
        $this->groups = $groups;
        $this->identifier = 'route' . $identifier;
        $this->middlewareDispatcher = new MiddlewareDispatcher($this, $callableResolver, $container);
    }

    public function getCallableResolver(): CallableResolverInterface
    {
        return $this->callableResolver;
    }

    public function getInvocationStrategy(): InvocationStrategyInterface
    {
        return $this->invocationStrategy;
    }


    public function setInvocationStrategy(InvocationStrategyInterface $invocationStrategy): RouteInterface
    {
        $this->invocationStrategy = $invocationStrategy;
        return $this;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern): RouteInterface
    {
        $this->pattern = $pattern;
        return $this;
    }


    public function getCallable()
    {
        return $this->callable;
    }


    public function setCallable($callable): RouteInterface
    {
        $this->callable = $callable;
        return $this;
    }


    public function getName(): ?string
    {
        return $this->name;
    }


    public function setName(string $name): RouteInterface
    {
        $this->name = $name;
        return $this;
    }


    public function getIdentifier(): string
    {
        return $this->identifier;
    }


    public function getArgument(string $name, ?string $default = null): ?string
    {
        if (array_key_exists($name, $this->arguments)) {
            return $this->arguments[$name];
        }
        return $default;
    }


    public function getArguments(): array
    {
        return $this->arguments;
    }


    public function setArguments(array $arguments, bool $includeInSavedArguments = true): RouteInterface
    {
        if ($includeInSavedArguments) {
            $this->savedArguments = $arguments;
        }

        $this->arguments = $arguments;
        return $this;
    }


    public function getGroups(): array
    {
        return $this->groups;
    }


    public function add($middleware): RouteInterface
    {
        $this->middlewareDispatcher->add($middleware);
        return $this;
    }


    public function addMiddleware(MiddlewareInterface $middleware): RouteInterface
    {
        $this->middlewareDispatcher->addMiddleware($middleware);
        return $this;
    }


    public function prepare(array $arguments): RouteInterface
    {
        $this->arguments = array_replace($this->savedArguments, $arguments);
        return $this;
    }


    public function setArgument(string $name, string $value, bool $includeInSavedArguments = true): RouteInterface
    {
        if ($includeInSavedArguments) {
            $this->savedArguments[$name] = $value;
        }

        $this->arguments[$name] = $value;
        return $this;
    }


    public function run(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->groupMiddlewareAppended) {
            $this->appendGroupMiddlewareToRoute();
        }

        return $this->middlewareDispatcher->handle($request);
    }


    protected function appendGroupMiddlewareToRoute(): void
    {
        $inner = $this->middlewareDispatcher;
        $this->middlewareDispatcher = new MiddlewareDispatcher($inner, $this->callableResolver, $this->container);

        foreach (array_reverse($this->groups) as $group) {
            $group->appendMiddlewareToDispatcher($this->middlewareDispatcher);
        }

        $this->groupMiddlewareAppended = true;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->callableResolver instanceof AdvancedCallableResolverInterface) {
            $callable = $this->callableResolver->resolveRoute($this->callable);
        } else {
            $callable = $this->callableResolver->resolve($this->callable);
        }
        $strategy = $this->invocationStrategy;

        /** @var string[] $strategyImplements */
        $strategyImplements = class_implements($strategy);

        if (
            is_array($callable)
            && $callable[0] instanceof RequestHandlerInterface
            && !in_array(RequestHandlerInvocationStrategyInterface::class, $strategyImplements)
        ) {
            $strategy = new RequestHandler();
        }

        $response = $this->responseFactory->createResponse();
        return $strategy($callable, $request, $response, $this->arguments);
    }

}