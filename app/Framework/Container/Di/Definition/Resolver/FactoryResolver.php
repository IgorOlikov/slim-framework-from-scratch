<?php

namespace Framework\Container\Di\Definition\Resolver;

use Framework\Container\Di\Definition\Definition;
use Framework\Container\Di\Definition\Exception\InvalidDefinition;
use Framework\Container\Di\Definition\FactoryDefinition;
use Framework\Container\Di\Invoker\FactoryParameterResolver;
use Framework\Container\Invoker\Exception\NotCallableException;
use Framework\Container\Invoker\Exception\NotEnoughParametersException;
use Framework\Container\Invoker\Invoker;
use Framework\Container\Invoker\ParameterResolver\AssociativeArrayResolver;
use Framework\Container\Invoker\ParameterResolver\DefaultValueResolver;
use Framework\Container\Invoker\ParameterResolver\NumericArrayResolver;
use Framework\Container\Invoker\ParameterResolver\ResolverChain;
use Framework\Psr\Container\ContainerInterface;

class FactoryResolver implements DefinitionResolver
{
    private ?Invoker $invoker = null;

    /**
     * The resolver needs a container. This container will be passed to the factory as a parameter
     * so that the factory can access other entries of the container.
     */
    public function __construct(
        private ContainerInterface $container,
        private DefinitionResolver $resolver,
    ) {
    }

    /**
     * Resolve a factory definition to a value.
     *
     * This will call the callable of the definition.
     *
     * @param FactoryDefinition $definition
     */
    public function resolve(Definition $definition, array $parameters = []) : mixed
    {
        if (! $this->invoker) {
            $parameterResolver = new ResolverChain([
                new AssociativeArrayResolver,
                new FactoryParameterResolver($this->container),
                new NumericArrayResolver,
                new DefaultValueResolver,
            ]);

            $this->invoker = new Invoker($parameterResolver, $this->container);
        }

        $callable = $definition->getCallable();

        try {
            $providedParams = [$this->container, $definition];
            $extraParams = $this->resolveExtraParams($definition->getParameters());
            $providedParams = array_merge($providedParams, $extraParams, $parameters);

            return $this->invoker->call($callable, $providedParams);
        } catch (NotCallableException $e) {
            // Custom error message to help debugging
            if (is_string($callable) && class_exists($callable) && method_exists($callable, '__invoke')) {
                throw new InvalidDefinition(sprintf(
                    'Entry "%s" cannot be resolved: factory %s. Invokable classes cannot be automatically resolved if autowiring is disabled on the container, you need to enable autowiring or define the entry manually.',
                    $definition->getName(),
                    $e->getMessage()
                ));
            }

            throw new InvalidDefinition(sprintf(
                'Entry "%s" cannot be resolved: factory %s',
                $definition->getName(),
                $e->getMessage()
            ));
        } catch (NotEnoughParametersException $e) {
            throw new InvalidDefinition(sprintf(
                'Entry "%s" cannot be resolved: %s',
                $definition->getName(),
                $e->getMessage()
            ));
        }
    }

    public function isResolvable(Definition $definition, array $parameters = []) : bool
    {
        return true;
    }

    private function resolveExtraParams(array $params) : array
    {
        $resolved = [];
        foreach ($params as $key => $value) {
            // Nested definitions
            if ($value instanceof Definition) {
                $value = $this->resolver->resolve($value);
            }
            $resolved[$key] = $value;
        }

        return $resolved;
    }
}