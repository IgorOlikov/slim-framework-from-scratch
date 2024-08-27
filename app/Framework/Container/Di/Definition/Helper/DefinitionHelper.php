<?php

namespace Framework\Container\Di\Definition\Helper;

use Framework\Container\Di\Definition\Definition;

interface DefinitionHelper
{
    public function getDefinition(string $entryName) : Definition;
}