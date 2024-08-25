<?php

namespace Framework\Core\Routing;

use Framework\FastRouter\DataGenerator\GroupCountBased;
use Framework\FastRouter\RouteCollector as FastRouterCollector;
use Framework\FastRouter\RouteParser\Std;

use Framework\Core\Interfaces\DispatcherInterface;
use Framework\Core\Interfaces\RouteCollectorInterface;

use Override;
use function Framework\FastRouter\cachedDispatcher;
use function Framework\FastRouter\simpleDispatcher;

require __DIR__ . '/../../FastRouter/functions.php';


class Dispatcher implements DispatcherInterface
{
    private RouteCollectorInterface $routeCollector;

    private ?FastRouterDispatcher $dispatcher = null;

    public function __construct(RouteCollectorInterface $routeCollector)
    {
        $this->routeCollector = $routeCollector;
    }


    public function dispatch(string $method, string $uri): RoutingResults
    {
        $dispatcher = $this->createDispatcher();

        $results = $dispatcher->dispatch($method, $uri);


        //dd($results[1][0]);

        return new RoutingResults($this, $method, $uri, $results[0], $results[1][0], $results[2]);
    }

    #[Override] public function getAllowedMethods(string $uri): array
    {
        $dispatcher = $this->createDispatcher();
        return $dispatcher->getAllowedMethods($uri);
    }

    protected function createDispatcher(): FastRouterDispatcher
    {
        if ($this->dispatcher) {
            return $this->dispatcher;
        }

        $routeDefinitionCallback = function (FastRouterCollector $r): void {
            $basePath = $this->routeCollector->getBasePath();

            foreach ($this->routeCollector->getRoutes() as $route) {
                $r->addRoute($route->getMethods(), $basePath . $route->getPattern(), $route->getIdentifier());
            }
        };

        $cacheFile = $this->routeCollector->getCacheFile();

        if ($cacheFile) {
            /** @var FastRouterDispatcher $dispatcher */
            $dispatcher = cachedDispatcher($routeDefinitionCallback, [
                'dataGenerator' => GroupCountBased::class,
                'dispatcher' => FastRouterDispatcher::class,
                'routeParser' => new Std(),
                'cacheFile' => $cacheFile,
            ]);

        } else {
            /** @var FastRouterDispatcher $dispatcher */
            $dispatcher = simpleDispatcher($routeDefinitionCallback, [
                'dataGenerator' => GroupCountBased::class,
                'dispatcher' => FastRouterDispatcher::class,
                'routeParser' => new Std(),
            ]);
        }

        $this->dispatcher = $dispatcher;
        return $this->dispatcher;
    }

}