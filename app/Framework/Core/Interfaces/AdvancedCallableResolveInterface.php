<?php

namespace Framework\Core\Interfaces;

interface AdvancedCallableResolveInterface
{
    public function resolveRoute($toResolve): callable;

    public function resolveMiddleware($toResolve): callable;
}