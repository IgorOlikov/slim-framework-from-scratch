<?php

namespace Framework\Psr\Log;

interface LoggerAwareInterface
{
    public function setLogger(LoggerInterface $logger): void;
}