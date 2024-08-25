<?php

namespace Framework\FastRouter\Dispatcher;

use Framework\FastRouter\Dispatcher\Result\Matched;

class GroupPosBased extends RegexBasedAbstract
{
    protected function dispatchVariableRoute(array $routeData, string $uri): ?Matched
    {
        foreach ($routeData as $data) {
            if (preg_match($data['regex'], $uri, $matches) !== 1) {
                continue;
            }

            // find first non-empty match
            // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedFor
            for ($i = 1; $matches[$i] === ''; ++$i) {
            }

            assert(isset($i));

            [$handler, $varNames, $extraParameters] = $data['routeMap'][$i];

            $vars = [];
            foreach ($varNames as $varName) {
                $vars[$varName] = $matches[$i++];
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