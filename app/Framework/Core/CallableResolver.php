<?php

namespace Framework\Core;

use Framework\Core\Interfaces\AdvancedCallableResolveInterface;
use Framework\Psr\Container\ContainerInterface;
use Override;

class CallableResolver implements AdvancedCallableResolveInterface
{
    public static string $callablePattern = '!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!';

    private ?ContainerInterface $container;

    public function __construct(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    #[Override] public function resolveRoute($toResolve): callable
    {
        // TODO: Implement resolveRoute() method.
    }

    #[Override] public function resolveMiddleware($toResolve): callable
    {
        // TODO: Implement resolveMiddleware() method.
    }
}