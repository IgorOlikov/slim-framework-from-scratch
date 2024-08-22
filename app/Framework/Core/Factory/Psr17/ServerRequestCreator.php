<?php

namespace Framework\Core\Factory\Psr17;

use Closure;
use Framework\Core\Interfaces\ServerRequestCreatorInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Override;

class ServerRequestCreator implements ServerRequestCreatorInterface
{
    protected object|string $serverRequestCreator;

    protected string $serverRequestCreatorMethod;

    public function __construct($serverRequestCreator, string $serverRequestCreatorMethod)
    {
        $this->serverRequestCreator = $serverRequestCreator;
        $this->serverRequestCreatorMethod = $serverRequestCreatorMethod;
    }

    #[Override] public function createServerRequestFromGlobals(): ServerRequestInterface
    {
        $callable = [$this->serverRequestCreator, $this->serverRequestCreatorMethod];

        return (Closure::fromCallable($callable))();
    }
}