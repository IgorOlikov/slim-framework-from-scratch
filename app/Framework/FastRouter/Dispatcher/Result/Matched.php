<?php

namespace Framework\FastRouter\Dispatcher\Result;

use ArrayAccess;
use Framework\FastRouter\Dispatcher;
use OutOfBoundsException;
use Override;
use RuntimeException;

class Matched implements ArrayAccess
{
    public mixed $handler;

    public array $variables = [];

    public array $extraParameters = [];

    #[Override] public function offsetExists(mixed $offset): bool
    {
        return $offset >= 0 && $offset <= 2;
    }

    #[Override] public function offsetGet(mixed $offset): mixed
    {
        return match ($offset) {
            0 => Dispatcher::FOUND,
            1 => $this->handler,
            2 => $this->variables,
            default => throw new OutOfBoundsException()
        };
    }

    #[Override] public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new RuntimeException('Result cannot be changed');
    }

    #[Override] public function offsetUnset(mixed $offset): void
    {
        throw new RuntimeException('Result cannot be changed');
    }
}