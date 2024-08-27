<?php

namespace Framework\Container\Bridge;

use Framework\Container\Di\Container;
use Framework\Container\Invoker\Invoker;
use Framework\Container\Invoker\ParameterResolver\AssociativeArrayResolver;
use Framework\Container\Invoker\ParameterResolver\Container\TypeHintContainerResolver;
use Framework\Container\Invoker\ParameterResolver\DefaultValueResolver;
use Framework\Container\Invoker\ParameterResolver\ResolverChain;
use Framework\Core\App;
use Framework\Core\Factory\AppFactory;
use Framework\Core\Interfaces\CallableResolverInterface;
use Framework\Psr\Container\ContainerInterface;
use Framework\Container\Invoker\CallableResolver as InvokerCallableResolver;

class Bridge
{
    public static function create(?ContainerInterface $container = null): App
    {
        $container = $container ?: new Container;

        $callableResolver = new InvokerCallableResolver($container);

        $container->set(CallableResolverInterface::class, new CallableResolver($callableResolver));
        $app = AppFactory::createFromContainer($container);

        $container->set(App::class, $app);

        $controllerInvoker = static::createControllerInvoker($container);
        $app->getRouteCollector()->setDefaultInvocationStrategy($controllerInvoker);

        return $app;
    }

    private static function createControllerInvoker(ContainerInterface $container): ControllerInvoker
    {
        $resolvers = [
            // Inject parameters by name first
            new AssociativeArrayResolver,
            // Then inject services by type-hints for those that weren't resolved
            new TypeHintContainerResolver($container),
            // Then fall back on parameters default values for optional route parameters
            new DefaultValueResolver,
        ];

        $invoker = new Invoker(new ResolverChain($resolvers), $container);

        return new ControllerInvoker($invoker);
    }
}