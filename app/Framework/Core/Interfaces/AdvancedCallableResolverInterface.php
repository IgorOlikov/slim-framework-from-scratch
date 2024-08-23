<?php

namespace Framework\Core\Interfaces;

interface AdvancedCallableResolverInterface extends CallableResolverInterface
{
    public function resolveRoute($toResolve): callable;

    public function resolveMiddleware($toResolve): callable;
}