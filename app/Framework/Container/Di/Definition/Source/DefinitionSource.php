<?php

namespace Framework\Container\Di\Definition\Source;

use Framework\Container\Di\Definition\Definition;
use Framework\Container\Di\Definition\Exception\InvalidDefinition;

interface DefinitionSource
{
    /**
     * Returns the DI definition for the entry name.
     *
     * @throws InvalidDefinition An invalid definition was found.
     */
    public function getDefinition(string $name) : Definition|null;

    /**
     * @return array<string,Definition> Definitions indexed by their name.
     */
    public function getDefinitions() : array;
}