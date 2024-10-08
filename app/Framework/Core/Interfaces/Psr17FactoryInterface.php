<?php

namespace Framework\Core\Interfaces;

use Framework\Psr\Http\Factory\ResponseFactoryInterface;
use Framework\Psr\Http\Factory\StreamFactoryInterface;

interface Psr17FactoryInterface
{
    public static function getResponseFactory(): ResponseFactoryInterface;

    public static function getStreamFactory(): StreamFactoryInterface;

    public static function getServerRequestCreator(): ServerRequestCreatorInterface;

    public static function isResponseFactoryAvailable(): bool;

    public static function isStreamFactoryAvailable(): bool;

    public static function isServerRequestCreatorAvailable(): bool;
}