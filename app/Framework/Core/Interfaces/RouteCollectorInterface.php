<?php

namespace Framework\Core\Interfaces;

interface RouteCollectorInterface
{
    public function getRouteParser(): RouteParserInterface;

    public function getDefaultInvocationStrategy(): InvocationStrategyInterface;

    public function setDefaultInvocationStrategy(InvocationStrategyInterface $strategy): RouteCollectorInterface;

    public function getCacheFile(): ?string;

    public function setCacheFile(string $cacheFile): RouteCollectorInterface;

    public function getBasePath(): string;

    public function setBasePath(string $basePath): RouteCollectorInterface;

    public function getRoutes(): array;

    public function getNamedRoute(string $name): RouteInterface;

    public function removeNamedRoute(string $name): RouteCollectorInterface;

    public function lookupRoute(string $identifier): RouteInterface;

    public function group(string $pattern, $callable): RouteGroupInterface;

    public function map(array $methods, string $pattern, $handler): RouteInterface;
}