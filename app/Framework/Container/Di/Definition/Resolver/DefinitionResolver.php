<?php

namespace Framework\Container\Di\Definition\Resolver;

use Framework\Container\Di\Definition\Definition;
use Framework\Container\Di\Definition\Exception\InvalidDefinition;
use Framework\Container\Di\DependencyException;

interface DefinitionResolver
{
    /**
     * Resolve a definition to a value.
     *
     * @param Definition $definition Object that defines how the value should be obtained.
     * @psalm-param T $definition
     * @param array      $parameters Optional parameters to use to build the entry.
     * @return mixed Value obtained from the definition.
     *
     * @throws InvalidDefinition If the definition cannot be resolved.
     * @throws DependencyException
     */
    public function resolve(Definition $definition, array $parameters = []) : mixed;

    /**
     * Check if a definition can be resolved.
     *
     * @param Definition $definition Object that defines how the value should be obtained.
     * @psalm-param T $definition
     * @param array      $parameters Optional parameters to use to build the entry.
     */
    public function isResolvable(Definition $definition, array $parameters = []) : bool;
}