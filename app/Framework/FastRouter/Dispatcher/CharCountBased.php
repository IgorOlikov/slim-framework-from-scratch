<?php

namespace Framework\FastRouter\Dispatcher;

use Framework\FastRouter\Dispatcher\Result\Matched;

class CharCountBased extends RegexBasedAbstract
{
    protected function dispatchVariableRoute(array $routeData, string $uri): ?Matched
    {
        foreach ($routeData as $data) {
            assert(isset($data['suffix']));

            if (preg_match($data['regex'], $uri . $data['suffix'], $matches) !== 1) {
                continue;
            }

            [$handler, $varNames, $extraParameters] = $data['routeMap'][end($matches)];

            $vars = [];
            $i = 0;
            foreach ($varNames as $varName) {
                $vars[$varName] = $matches[++$i];
            }

            $result = new Matched();
            $result->handler = $handler;
            $result->variables = $vars;
            $result->extraParameters = $extraParameters;

            return $result;
        }

        return null;
    }
}