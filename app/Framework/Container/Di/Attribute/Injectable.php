<?php

namespace Framework\Container\Di\Attribute;


use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Injectable
{
    /**
     * @param bool|null $lazy Should the object be lazy-loaded.
     */
    public function __construct(
        private ?bool $lazy = null,
    ) {
    }

    public function isLazy() : bool|null
    {
        return $this->lazy;
    }
}