<?php

namespace Framework\Core\Interfaces;

interface CallableResolverInterface
{
    public function resolve($toResolve): callable;
}