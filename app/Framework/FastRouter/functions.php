<?php


namespace Framework\FastRouter;

use Framework\FastRouter\Cache\FileCache;
use LogicException;



if (! function_exists('Framework\FastRouter\simpleDispatcher')) {


    function simpleDispatcher(callable $routeDefinitionCallback, array $options = []): Dispatcher
    {
        return cachedDispatcher(
            $routeDefinitionCallback,
            ['cacheDisabled' => true] + $options,
        );
    }


    function cachedDispatcher(callable $routeDefinitionCallback, array $options = []): Dispatcher
    {
        $options += [
            'routeParser' => RouteParser\Std::class,
            'dataGenerator' => DataGenerator\MarkBased::class,
            'dispatcher' => Dispatcher\MarkBased::class,
            'routeCollector' => RouteCollector::class,
            'cacheDisabled' => false,
            'cacheDriver' => FileCache::class,
        ];

        $loader = static function () use ($routeDefinitionCallback, $options): array {
            $routeCollector = new $options['routeCollector'](
                new $options['routeParser'](),
                new $options['dataGenerator']()
            );

            $routeDefinitionCallback($routeCollector);

            return $routeCollector->processedRoutes();
        };

        if ($options['cacheDisabled'] === true) {
            return new $options['dispatcher']($loader());
        }

        $cacheKey = $options['cacheKey'] ?? $options['cacheFile'] ?? null;

        if ($cacheKey === null) {
            throw new LogicException('Must specify "cacheKey" option');
        }

        $cache = $options['cacheDriver'];

        if (is_string($cache)) {
            $cache = new $cache();
        }

        return new $options['dispatcher']($cache->get($cacheKey, $loader));
    }


}