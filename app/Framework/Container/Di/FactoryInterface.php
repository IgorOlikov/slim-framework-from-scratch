<?php

namespace Framework\Container\Di;

interface FactoryInterface
{
    public function make(string $name, array $parameters = []) : mixed;
}