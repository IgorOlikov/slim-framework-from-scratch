<?php

namespace Framework\FastRouter\Cache;

use Framework\FastRouter\Cache;
use Framework\Psr\SimpleCache\CacheInterface;
use Framework\Psr\SimpleCache\InvalidArgumentException;
use Override;

final class Psr16Cache implements Cache
{

    public function __construct(private readonly CacheInterface $cache)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $key, callable $loader): array
    {
        $result = $this->cache->get($key);

        if (is_array($result)) {

            return $result;
        }

        $data = $loader();
        $this->cache->set($key, $data);

        return $data;
    }
}