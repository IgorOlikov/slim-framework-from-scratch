<?php

namespace Framework\Psr7\Factory;


use Framework\Psr\Http\Factory\StreamFactoryInterface;
use Framework\Psr\Http\Message\StreamInterface;
use Framework\Psr7\Stream;
use InvalidArgumentException;
use Override;
use RuntimeException;
use ValueError;

class StreamFactory implements StreamFactoryInterface
{

    #[Override] public function createStream(string $content = ''): StreamInterface
    {
        $resource = fopen('php://temp', 'rw+');

        if (!is_resource($resource)) {
            throw new RuntimeException('StreamFactory::createStream() could not open temporary file stream.');
        }

        fwrite($resource, $content);
        rewind($resource);

        return $this->createStreamFromResource($resource);
    }

    #[Override] public function createStreamFromFile(string $filename, string $mode = 'r', ?StreamInterface $cache = null): StreamInterface
    {
        set_error_handler(
            static function (int $errno, string $errstr) use ($filename, $mode): void {
                throw new RuntimeException(
                    "Unable to open $filename using mode $mode: $errstr",
                    $errno
                );
            }
        );

        try {
            $resource = fopen($filename, $mode);
        } catch (ValueError $exception) {
            throw new RuntimeException("Unable to open $filename using mode $mode: " . $exception->getMessage());
        } finally {
            restore_error_handler();
        }

        if (!is_resource($resource)) {
            throw new RuntimeException(
                "StreamFactory::createStreamFromFile() could not create resource from file `$filename`"
            );
        }

        return new Stream($resource, $cache);
    }

    #[Override] public function createStreamFromResource($resource, ?StreamInterface $cache = null): StreamInterface
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException(
                'Parameter 1 of StreamFactory::createStreamFromResource() must be a resource.'
            );
        }

        return new Stream($resource, $cache);
    }
}