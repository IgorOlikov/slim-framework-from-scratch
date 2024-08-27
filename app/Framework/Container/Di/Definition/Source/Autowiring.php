<?php

namespace Framework\Container\Di\Definition\Source;

use Framework\Container\Di\Definition\ObjectDefinition;

interface Autowiring
{
    public function autowire(string $name, ?ObjectDefinition $definition = null) : ObjectDefinition|null;
}