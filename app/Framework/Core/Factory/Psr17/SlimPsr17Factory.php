<?php

namespace Framework\Core\Factory\Psr17;

class SlimPsr17Factory extends Psr17Factory
{
    protected static string $responseFactoryClass = 'Framework\Psr7\Factory\ResponseFactory';
    protected static string $streamFactoryClass = 'Framework\Psr7\Factory\StreamFactory';
    protected static string $serverRequestCreatorClass = 'Framework\Psr7\Factory\ServerRequestFactory';
    protected static string $serverRequestCreatorMethod = 'createFromGlobals';
}