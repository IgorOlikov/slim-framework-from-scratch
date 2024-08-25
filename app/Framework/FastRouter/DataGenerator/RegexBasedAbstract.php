<?php

namespace Framework\FastRouter\DataGenerator;

use Framework\FastRouter\BadRouteException;
use Framework\FastRouter\DataGenerator;
use Framework\FastRouter\Route;

abstract class RegexBasedAbstract implements DataGenerator
{

    protected array $staticRoutes = [];


    protected array $methodToRegexToRoutesMap = [];

    abstract protected function getApproxChunkSize(): int;


    abstract protected function processChunk(array $regexToRoutesMap): array;


    public function addRoute(string $httpMethod, array $routeData, mixed $handler, array $extraParameters = []): void
    {
        if ($this->isStaticRoute($routeData)) {
            $this->addStaticRoute($httpMethod, $routeData, $handler, $extraParameters);
        } else {
            $this->addVariableRoute($httpMethod, $routeData, $handler, $extraParameters);
        }
    }


    public function getData(): array
    {
        if ($this->methodToRegexToRoutesMap === []) {
            return [$this->staticRoutes, []];
        }

        return [$this->staticRoutes, $this->generateVariableRouteData()];
    }


    private function generateVariableRouteData(): array
    {
        $data = [];
        foreach ($this->methodToRegexToRoutesMap as $method => $regexToRoutesMap) {
            $chunkSize = $this->computeChunkSize(count($regexToRoutesMap));
            $chunks = array_chunk($regexToRoutesMap, $chunkSize, true);
            $data[$method] = array_map([$this, 'processChunk'], $chunks);
        }

        return $data;
    }


    private function computeChunkSize(int $count): int
    {
        $numParts = max(1, round($count / $this->getApproxChunkSize()));
        $size = (int) ceil($count / $numParts);
        assert($size > 0);

        return $size;
    }


    private function isStaticRoute(array $routeData): bool
    {
        return count($routeData) === 1 && is_string($routeData[0]);
    }


    private function addStaticRoute(string $httpMethod, array $routeData, mixed $handler, array $extraParameters): void
    {
        $routeStr = $routeData[0];
        assert(is_string($routeStr));

        if (isset($this->staticRoutes[$httpMethod][$routeStr])) {
            throw BadRouteException::alreadyRegistered($routeStr, $httpMethod);
        }

        if (isset($this->methodToRegexToRoutesMap[$httpMethod])) {
            foreach ($this->methodToRegexToRoutesMap[$httpMethod] as $route) {
                if ($route->matches($routeStr)) {
                    throw BadRouteException::shadowedByVariableRoute($routeStr, $route->regex, $httpMethod);
                }
            }
        }

        $this->staticRoutes[$httpMethod][$routeStr] = [$handler, $extraParameters];
    }


    private function addVariableRoute(string $httpMethod, array $routeData, mixed $handler, array $extraParameters): void
    {
        $route = new Route($httpMethod, $routeData, $handler, $extraParameters);
        $regex = $route->regex;

        if (isset($this->methodToRegexToRoutesMap[$httpMethod][$regex])) {
            throw BadRouteException::alreadyRegistered($regex, $httpMethod);
        }

        $this->methodToRegexToRoutesMap[$httpMethod][$regex] = $route;
    }
}