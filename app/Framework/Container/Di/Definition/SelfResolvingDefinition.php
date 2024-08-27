<?php

namespace Framework\Container\Di\Definition;

use Framework\Psr\Container\ContainerInterface;

interface SelfResolvingDefinition
{
    public function resolve(ContainerInterface $container) : mixed;

    /**
     * Check if a definition can be resolved.
     */
    public function isResolvable(ContainerInterface $container) : bool;
}