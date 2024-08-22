<?php

namespace Framework\Core\Factory\Psr17;

use Framework\Core\Interfaces\Psr17FactoryProviderInterface;
use Override;

class Psr17FactoryProvider implements Psr17FactoryProviderInterface
{

    protected static array $factories = [
        SlimPsr17Factory::class
    ];

    #[Override] public static function getFactories(): array
    {
        return static::$factories;
    }

    #[Override] public static function setFactories(array $factories): void
    {
        static::$factories = $factories;
    }

    #[Override] public static function addFactory(string $factory): void
    {
        array_unshift(static::$factories, $factory);
    }
}