<?php

namespace Framework\FastRouter\GenerateUri;

use Framework\FastRouter\GenerateUri;

final readonly class FromProcessedConfiguration implements GenerateUri
{
    public function __construct(private array $processedConfiguration)
    {
    }


    public function forRoute(string $name, array $substitutions = []): string
    {
        if (! array_key_exists($name, $this->processedConfiguration)) {
            throw UriCouldNotBeGenerated::routeIsUndefined($name);
        }

        $missingParameters = [];

        foreach ($this->processedConfiguration[$name] as $parsedRoute) {
            $missingParameters = $this->missingParameters($parsedRoute, $substitutions);

            // Only attempt to generate the path if we have the necessary info
            if (count($missingParameters) === 0) {
                return $this->generatePath($name, $parsedRoute, $substitutions);
            }
        }

        assert(count($missingParameters) > 0);

        throw UriCouldNotBeGenerated::insufficientParameters(
            $name,
            $missingParameters,
            array_keys($substitutions),
        );
    }


    private function missingParameters(array $parts, array $substitutions): array
    {
        $missingParameters = [];

        foreach ($parts as $part) {
            if (is_string($part) || array_key_exists($part[0], $substitutions)) {
                continue;
            }

            $missingParameters[] = $part[0];
        }

        return $missingParameters;
    }


    private function generatePath(string $route, array $parsedRoute, array $substitutions): string
    {
        $path = '';

        foreach ($parsedRoute as $part) {
            if (is_string($part)) {
                $path .= $part;

                continue;
            }

            [$parameterName, $regex] = $part;

            if (preg_match('~^' . $regex . '$~u', $substitutions[$parameterName]) !== 1) {
                throw UriCouldNotBeGenerated::parameterDoesNotMatchThePattern($route, $parameterName, $regex);
            }

            $path .= $substitutions[$parameterName];
        }

        return $path;
    }
}