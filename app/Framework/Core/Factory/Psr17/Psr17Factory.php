<?php

namespace Framework\Core\Factory\Psr17;

use Framework\Core\Interfaces\Psr17FactoryInterface;
use Framework\Core\Interfaces\ServerRequestCreatorInterface;
use Framework\Psr\Http\Factory\ResponseFactoryInterface;
use Framework\Psr\Http\Factory\StreamFactoryInterface;
use RuntimeException;
use Override;

class Psr17Factory implements Psr17FactoryInterface
{

    protected static string $responseFactoryClass;

    protected static string $streamFactoryClass;

    protected static string $serverRequestCreatorClass;

    protected static string $serverRequestCreatorMethod;


    #[Override] public static function getResponseFactory(): ResponseFactoryInterface
    {
        if (!static::isResponseFactoryAvailable() || !(($responseFactory = new static::$responseFactoryClass()) instanceof ResponseFactoryInterface)) {
            throw new RuntimeException(get_called_class() . 'could not instantiate a response factory!');
        }

        return $responseFactory;
    }

    #[Override] public static function getStreamFactory(): StreamFactoryInterface
    {
        if (
            !static::isStreamFactoryAvailable()
            || !(($streamFactory = new static::$streamFactoryClass()) instanceof StreamFactoryInterface)
        ) {
            throw new RuntimeException(get_called_class() . ' could not instantiate a stream factory.');
        }

        return $streamFactory;
    }

    #[Override] public static function getServerRequestCreator(): ServerRequestCreatorInterface
    {
        if (!static::isServerRequestCreatorAvailable()) {
            throw new RuntimeException(get_called_class() . ' could not instantiate a server request creator.');
        }

        return new ServerRequestCreator(static::$serverRequestCreatorClass, static::$serverRequestCreatorMethod);
    }

    #[Override] public static function isResponseFactoryAvailable(): bool
    {
        return static::$responseFactoryClass && class_exists(static::$responseFactoryClass);
    }

    #[Override] public static function isStreamFactoryAvailable(): bool
    {
        return static::$streamFactoryClass && class_exists(static::$streamFactoryClass);
    }

    #[Override] public static function isServerRequestCreatorAvailable(): bool
    {
        return (
            static::$serverRequestCreatorClass
            && static::$serverRequestCreatorMethod
            && class_exists(static::$serverRequestCreatorClass)
        );
    }
}