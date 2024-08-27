<?php

namespace Framework\Container\Di\Definition;

interface ExtendsPreviousDefinition extends Definition
{
    public function setExtendedDefinition(Definition $definition) : void;
}