<?php

namespace Framework\Psr\Log;

class NullLogger extends AbstractLogger
{

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        // TODO: Implement log() method.
    }
}