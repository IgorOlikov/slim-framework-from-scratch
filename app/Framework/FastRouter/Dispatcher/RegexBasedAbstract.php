<?php

namespace Framework\FastRouter\Dispatcher;





use Framework\FastRouter\Dispatcher\Result\Matched;
use Framework\FastRouter\Dispatcher\Result\MethodNotAllowed;
use Framework\FastRouter\Dispatcher\Result\NotMatched;

abstract class RegexBasedAbstract implements \Framework\FastRouter\Dispatcher
{
    protected array $staticRouteMap = [];


    protected array $variableRouteData = [];


    public function __construct(array $data)
    {
        [$this->staticRouteMap, $this->variableRouteData] = $data;
    }


    abstract protected function dispatchVariableRoute(array $routeData, string $uri): ?Matched;

    public function dispatch(string $httpMethod, string $uri)
    {
        if (isset($this->staticRouteMap[$httpMethod][$uri])) {
            $result = new Matched();
            $result->handler = $this->staticRouteMap[$httpMethod][$uri][0];
            $result->extraParameters = $this->staticRouteMap[$httpMethod][$uri][1];

            return $result;
        }

        if (isset($this->variableRouteData[$httpMethod])) {
            $result = $this->dispatchVariableRoute($this->variableRouteData[$httpMethod], $uri);
            if ($result !== null) {
                return $result;
            }
        }

        // For HEAD requests, attempt fallback to GET
        if ($httpMethod === 'HEAD') {
            if (isset($this->staticRouteMap['GET'][$uri])) {
                $result = new Matched();
                $result->handler = $this->staticRouteMap['GET'][$uri][0];
                $result->extraParameters = $this->staticRouteMap['GET'][$uri][1];

                return $result;
            }

            if (isset($this->variableRouteData['GET'])) {
                $result = $this->dispatchVariableRoute($this->variableRouteData['GET'], $uri);
                if ($result !== null) {
                    return $result;
                }
            }
        }

        // If nothing else matches, try fallback routes
        if (isset($this->staticRouteMap['*'][$uri])) {
            $result = new Matched();
            $result->handler = $this->staticRouteMap['*'][$uri][0];
            $result->extraParameters = $this->staticRouteMap['*'][$uri][1];

            return $result;
        }

        if (isset($this->variableRouteData['*'])) {
            $result = $this->dispatchVariableRoute($this->variableRouteData['*'], $uri);
            if ($result !== null) {
                return $result;
            }
        }

        // Find allowed methods for this URI by matching against all other HTTP methods as well
        $allowedMethods = [];

        foreach ($this->staticRouteMap as $method => $uriMap) {
            if ($method === $httpMethod || ! isset($uriMap[$uri])) {
                continue;
            }

            $allowedMethods[] = $method;
        }

        foreach ($this->variableRouteData as $method => $routeData) {
            if ($method === $httpMethod) {
                continue;
            }

            $result = $this->dispatchVariableRoute($routeData, $uri);
            if ($result === null) {
                continue;
            }

            $allowedMethods[] = $method;
        }

        // If there are no allowed methods the route simply does not exist
        if ($allowedMethods !== []) {
            $result = new MethodNotAllowed();
            $result->allowedMethods = $allowedMethods;

            return $result;
        }

        return new NotMatched();
    }
}