<?php

namespace Framework\Psr7\Factory;

use Framework\Psr\Http\Factory\StreamFactoryInterface;
use Framework\Psr\Http\Message\StreamInterface;
use Override;

class StreamFactory implements StreamFactoryInterface
{

    #[Override] public function createStream(string $content = ''): StreamInterface
    {
        // TODO: Implement createStream() method.
    }

    #[Override] public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        // TODO: Implement createStreamFromFile() method.
    }

    #[Override] public function createStreamFromResource($resource): StreamInterface
    {
        // TODO: Implement createStreamFromResource() method.
    }
}