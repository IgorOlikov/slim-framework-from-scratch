<?php

namespace Framework\Core;

use Framework\Psr\Log\AbstractLogger;
use Stringable;

class Logger extends AbstractLogger
{

    public function log($level, Stringable|string $message, array $context = []): void
    {
        error_log((string) $message);
    }
}