<?php

namespace Framework\FastRouter;

interface DataGenerator
{
    public function addRoute(string $httpMethod, array $routeData, mixed $handler, array $extraParameters = []): void;

    public function getData(): array;
}