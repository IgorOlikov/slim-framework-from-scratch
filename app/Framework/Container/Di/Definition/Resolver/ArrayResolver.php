<?php

namespace Framework\Container\Di\Definition\Resolver;

use Exception;
use Framework\Container\Di\Definition\ArrayDefinition;
use Framework\Container\Di\Definition\Definition;
use Framework\Container\Di\DependencyException;

class ArrayResolver implements DefinitionResolver
{
    /**
     * @param DefinitionResolver $definitionResolver Used to resolve nested definitions.
     */
    public function __construct(
        private DefinitionResolver $definitionResolver
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * Resolve an array definition to a value.
     *
     * An array definition can contain simple values or references to other entries.
     *
     * @param ArrayDefinition $definition
     */
    public function resolve(Definition $definition, array $parameters = []) : array
    {
        $values = $definition->getValues();

        // Resolve nested definitions
        array_walk_recursive($values, function (& $value, $key) use ($definition) {
            if ($value instanceof Definition) {
                $value = $this->resolveDefinition($value, $definition, $key);
            }
        });

        return $values;
    }

    public function isResolvable(Definition $definition, array $parameters = []) : bool
    {
        return true;
    }

    /**
     * @throws DependencyException
     */
    private function resolveDefinition(Definition $value, ArrayDefinition $definition, int|string $key) : mixed
    {
        try {
            return $this->definitionResolver->resolve($value);
        } catch (DependencyException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new DependencyException(sprintf(
                'Error while resolving %s[%s]. %s',
                $definition->getName(),
                $key,
                $e->getMessage()
            ), 0, $e);
        }
    }
}