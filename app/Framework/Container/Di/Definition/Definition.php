<?php

namespace Framework\Container\Di\Definition;

use Framework\Container\Di\Factory\RequestedEntry;

interface Definition extends RequestedEntry, \Stringable
{
    /**
     * Returns the name of the entry in the container.
     */
    public function getName() : string;

    /**
     * Set the name of the entry in the container.
     */
    public function setName(string $name) : void;

    /**
     * Apply a callable that replaces the definitions nested in this definition.
     */
    public function replaceNestedDefinitions(callable $replacer) : void;

    /**
     * Definitions can be cast to string for debugging information.
     */
    public function __toString() : string;
}