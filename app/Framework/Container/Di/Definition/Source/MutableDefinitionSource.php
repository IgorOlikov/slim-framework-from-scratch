<?php

namespace Framework\Container\Di\Definition\Source;

use Framework\Container\Di\Definition\Definition;

interface MutableDefinitionSource extends DefinitionSource
{
    public function addDefinition(Definition $definition) : void;
}