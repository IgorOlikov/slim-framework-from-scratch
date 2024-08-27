<?php

namespace Framework\Container\Di\Compiler;

use Framework\Container\Di\Factory\RequestedEntry;

class RequestedEntryHolder implements RequestedEntry
{
    public function __construct(
        private string $name,
    ) {
    }

    public function getName() : string
    {
        return $this->name;
    }
}