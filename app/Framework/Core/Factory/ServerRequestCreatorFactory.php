<?php

namespace Framework\Core\Factory;

use Framework\Core\Factory\Psr17\Psr17Factory;
use Framework\Core\Factory\Psr17\Psr17FactoryProvider;
use Framework\Core\Factory\Psr17\SlimHttpServerRequestCreator;
use Framework\Core\Interfaces\Psr17FactoryProviderInterface;
use Framework\Core\Interfaces\ServerRequestCreatorInterface;
use RuntimeException;

class ServerRequestCreatorFactory
{
    protected static ?Psr17FactoryProviderInterface $psr17FactoryProvider = null;

    protected static ?ServerRequestCreatorInterface $serverRequestCreator = null;

    protected static bool $slimHttpDecoratorsAutomaticDetectionEnabled = true;

    public static function create(): ServerRequestCreatorInterface
    {
        return static::determineServerRequestCreator();
    }


    public static function determineServerRequestCreator(): ServerRequestCreatorInterface
    {
        if (static::$serverRequestCreator) {
            return static::attemptServerRequestCreatorDecoration(static::$serverRequestCreator);
        }

        $psr17FactoryProvider = static::$psr17FactoryProvider ?? new Psr17FactoryProvider();

        /** @var Psr17Factory $psr17Factory */
        foreach ($psr17FactoryProvider->getFactories() as $psr17Factory) {
            if ($psr17Factory::isServerRequestCreatorAvailable()) {
                $serverRequestCreator = $psr17Factory::getServerRequestCreator();
                return static::attemptServerRequestCreatorDecoration($serverRequestCreator);
            }
        }

        throw new RuntimeException(
            "Could not detect any ServerRequest creator implementations. " .
            "Please install a supported implementation in order to use `App::run()` " .
            "without having to pass in a `ServerRequest` object. " .
            "See https://github.com/slimphp/Slim/blob/4.x/README.md for a list of supported implementations."
        );
    }

    protected static function attemptServerRequestCreatorDecoration(
        ServerRequestCreatorInterface $serverRequestCreator
    ): ServerRequestCreatorInterface {
        if (
            static::$slimHttpDecoratorsAutomaticDetectionEnabled
            && SlimHttpServerRequestCreator::isServerRequestDecoratorAvailable()
        ) {
            return new SlimHttpServerRequestCreator($serverRequestCreator);
        }

        return $serverRequestCreator;
    }

    public static function setPsr17FactoryProvider(Psr17FactoryProviderInterface $psr17FactoryProvider): void
    {
        static::$psr17FactoryProvider = $psr17FactoryProvider;
    }

    public static function setServerRequestCreator(ServerRequestCreatorInterface $serverRequestCreator): void
    {
        self::$serverRequestCreator = $serverRequestCreator;
    }

    public static function setSlimHttpDecoratorsAutomaticDetection(bool $enabled): void
    {
        static::$slimHttpDecoratorsAutomaticDetectionEnabled = $enabled;
    }
}