<?php

namespace Framework\Container\Invoker;

interface InvokerInterface
{
    public function call($callable, array $parameters = []);
}