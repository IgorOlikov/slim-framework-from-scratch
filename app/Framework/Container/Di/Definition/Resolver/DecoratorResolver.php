<?php

namespace Framework\Container\Di\Definition\Resolver;

use Framework\Container\Di\Definition\DecoratorDefinition;
use Framework\Container\Di\Definition\Definition;
use Framework\Container\Di\Definition\Exception\InvalidDefinition;
use Framework\Psr\Container\ContainerInterface;

class DecoratorResolver implements DefinitionResolver
{
    /**
     * The resolver needs a container. This container will be passed to the factory as a parameter
     * so that the factory can access other entries of the container.
     *
     * @param DefinitionResolver $definitionResolver Used to resolve nested definitions.
     */
    public function __construct(
        private ContainerInterface $container,
        private DefinitionResolver $definitionResolver
    ) {
    }

    /**
     * Resolve a decorator definition to a value.
     *
     * This will call the callable of the definition and pass it the decorated entry.
     *
     * @param DecoratorDefinition $definition
     */
    public function resolve(Definition $definition, array $parameters = []) : mixed
    {
        $callable = $definition->getCallable();

        if (! is_callable($callable)) {
            throw new InvalidDefinition(sprintf(
                'The decorator "%s" is not callable',
                $definition->getName()
            ));
        }

        $decoratedDefinition = $definition->getDecoratedDefinition();

        if (! $decoratedDefinition instanceof Definition) {
            if (! $definition->getName()) {
                throw new InvalidDefinition('Decorators cannot be nested in another definition');
            }

            throw new InvalidDefinition(sprintf(
                'Entry "%s" decorates nothing: no previous definition with the same name was found',
                $definition->getName()
            ));
        }

        $decorated = $this->definitionResolver->resolve($decoratedDefinition, $parameters);

        return $callable($decorated, $this->container);
    }

    public function isResolvable(Definition $definition, array $parameters = []) : bool
    {
        return true;
    }
}