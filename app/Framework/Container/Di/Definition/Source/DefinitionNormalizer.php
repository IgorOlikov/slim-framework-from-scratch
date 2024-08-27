<?php

namespace Framework\Container\Di\Definition\Source;

use Framework\Container\Di\Definition\ArrayDefinition;
use Framework\Container\Di\Definition\AutowireDefinition;
use Framework\Container\Di\Definition\DecoratorDefinition;
use Framework\Container\Di\Definition\Definition;
use Framework\Container\Di\Definition\Exception\InvalidDefinition;
use Framework\Container\Di\Definition\FactoryDefinition;
use Framework\Container\Di\Definition\ObjectDefinition;
use Framework\Container\Di\Definition\ValueDefinition;

class DefinitionNormalizer
{
    public function __construct(
        private Autowiring $autowiring,
    ) {
    }

    /**
     * Normalize a definition that is *not* nested in another one.
     *
     * This is usually a definition declared at the root of a definition array.
     *
     * @param string $name The definition name.
     * @param string[] $wildcardsReplacements Replacements for wildcard definitions.
     *
     * @throws InvalidDefinition
     */
    public function normalizeRootDefinition(mixed $definition, string $name, ?array $wildcardsReplacements = null) : Definition
    {
        if ($definition instanceof DefinitionHelper) {
            $definition = $definition->getDefinition($name);
        } elseif (is_array($definition)) {
            $definition = new ArrayDefinition($definition);
        } elseif ($definition instanceof \Closure) {
            $definition = new FactoryDefinition($name, $definition);
        } elseif (! $definition instanceof Definition) {
            $definition = new ValueDefinition($definition);
        }

        // For a class definition, we replace * in the class name with the matches
        // *Interface -> *Impl => FooInterface -> FooImpl
        if ($wildcardsReplacements && $definition instanceof ObjectDefinition) {
            $definition->replaceWildcards($wildcardsReplacements);
        }

        if ($definition instanceof AutowireDefinition) {
            /** @var AutowireDefinition $definition */
            $definition = $this->autowiring->autowire($name, $definition);
        }

        $definition->setName($name);

        try {
            $definition->replaceNestedDefinitions([$this, 'normalizeNestedDefinition']);
        } catch (InvalidDefinition $e) {
            throw InvalidDefinition::create($definition, sprintf(
                'Definition "%s" contains an error: %s',
                $definition->getName(),
                $e->getMessage()
            ), $e);
        }

        return $definition;
    }

    /**
     * Normalize a definition that is nested in another one.
     *
     * @throws InvalidDefinition
     */
    public function normalizeNestedDefinition(mixed $definition) : mixed
    {
        $name = '<nested definition>';

        if ($definition instanceof DefinitionHelper) {
            $definition = $definition->getDefinition($name);
        } elseif (is_array($definition)) {
            $definition = new ArrayDefinition($definition);
        } elseif ($definition instanceof \Closure) {
            $definition = new FactoryDefinition($name, $definition);
        }

        if ($definition instanceof DecoratorDefinition) {
            throw new InvalidDefinition('Decorators cannot be nested in another definition');
        }

        if ($definition instanceof AutowireDefinition) {
            $definition = $this->autowiring->autowire($name, $definition);
        }

        if ($definition instanceof Definition) {
            $definition->setName($name);

            // Recursively traverse nested definitions
            $definition->replaceNestedDefinitions([$this, 'normalizeNestedDefinition']);
        }

        return $definition;
    }
}