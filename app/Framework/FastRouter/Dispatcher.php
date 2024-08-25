<?php

namespace Framework\FastRouter;

use Framework\FastRouter\Dispatcher\Result\Matched;
use Framework\FastRouter\Dispatcher\Result\MethodNotAllowed;
use Framework\FastRouter\Dispatcher\Result\NotMatched;

interface Dispatcher
{
    public const int NOT_FOUND = 0;
    public const int FOUND = 1;
    public const int METHOD_NOT_ALLOWED = 2;


    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * Returns an object that also has an array shape with one of the following formats:
     *
     *     [self::NOT_FOUND]
     *     [self::METHOD_NOT_ALLOWED, ['GET', 'OTHER_ALLOWED_METHODS']]
     *     [self::FOUND, $handler, ['varName' => 'value', ...]]
     */
    public function dispatch(string $httpMethod, string $uri);
}