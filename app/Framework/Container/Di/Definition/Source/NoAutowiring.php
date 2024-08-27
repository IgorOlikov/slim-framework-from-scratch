<?php

namespace Framework\Container\Di\Definition\Source;

use Framework\Container\Di\Definition\Exception\InvalidDefinition;

class NoAutowiring implements Autowiring
{
    public function autowire(string $name, ?ObjectDefinition $definition = null) : ObjectDefinition|null
    {
        throw new InvalidDefinition(sprintf(
            'Cannot autowire entry "%s" because autowiring is disabled',
            $name
        ));
    }
}