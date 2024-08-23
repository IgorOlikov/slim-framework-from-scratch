<?php

namespace Framework\Core\Factory\Psr17;

use Framework\Core\Interfaces\ServerRequestCreatorInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

class SlimHttpServerRequestCreator implements ServerRequestCreatorInterface
{
    protected ServerRequestCreatorInterface $serverRequestCreator;

    protected static string $serverRequestDecoratorClass = 'Slim\Http\ServerRequest';

    public function __construct(ServerRequestCreatorInterface $serverRequestCreator)
    {
        $this->serverRequestCreator = $serverRequestCreator;
    }

    /**
     * {@inheritdoc}
     */
    public function createServerRequestFromGlobals(): ServerRequestInterface
    {
        if (!static::isServerRequestDecoratorAvailable()) {
            throw new RuntimeException('The Slim-Http ServerRequest decorator is not available.');
        }

        $request = $this->serverRequestCreator->createServerRequestFromGlobals();

        if (
            !((
                $decoratedServerRequest = new static::$serverRequestDecoratorClass($request)
                ) instanceof ServerRequestInterface)
        ) {
            throw new RuntimeException(get_called_class() . ' could not instantiate a decorated server request.');
        }

        return $decoratedServerRequest;
    }

    public static function isServerRequestDecoratorAvailable(): bool
    {
        return class_exists(static::$serverRequestDecoratorClass);
    }
}