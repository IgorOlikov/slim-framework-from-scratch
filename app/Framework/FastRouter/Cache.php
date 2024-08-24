<?php

namespace Framework\FastRouter;

interface Cache
{
    public function get(string $key, callable $loader): array;
}