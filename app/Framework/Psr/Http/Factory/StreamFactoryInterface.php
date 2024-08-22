<?php

namespace Framework\Psr\Http\Factory;

use Framework\Psr\Http\Message\StreamInterface;

interface StreamFactoryInterface
{
    public function createStream(string $content = ''): StreamInterface;

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface;

    public function createStreamFromResource($resource): StreamInterface;
}