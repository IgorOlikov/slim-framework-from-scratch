<?php

namespace Framework\Container\Di\Definition;

use Framework\Psr\Container\ContainerInterface;

class ValueDefinition implements Definition, SelfResolvingDefinition
{
    /**
     * Entry name.
     */
    private string $name = '';

    public function __construct(
        private mixed $value,
    ) {
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function getValue() : mixed
    {
        return $this->value;
    }

    public function resolve(ContainerInterface $container) : mixed
    {
        return $this->getValue();
    }

    public function isResolvable(ContainerInterface $container) : bool
    {
        return true;
    }

    public function replaceNestedDefinitions(callable $replacer) : void
    {
        // no nested definitions
    }

    public function __toString() : string
    {
        return sprintf('Value (%s)', var_export($this->value, true));
    }
}