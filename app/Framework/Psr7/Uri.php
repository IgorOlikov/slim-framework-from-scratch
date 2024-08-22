<?php

namespace Framework\Psr7;

use Framework\Psr\Http\Message\UriInterface;
use Override;

class Uri implements UriInterface
{

    #[Override] public function getScheme(): string
    {
        // TODO: Implement getScheme() method.
    }

    #[Override] public function getAuthority(): string
    {
        // TODO: Implement getAuthority() method.
    }

    #[Override] public function getUserInfo(): string
    {
        // TODO: Implement getUserInfo() method.
    }

    #[Override] public function getHost(): string
    {
        // TODO: Implement getHost() method.
    }

    #[Override] public function getPort(): ?int
    {
        // TODO: Implement getPort() method.
    }

    #[Override] public function getPath(): string
    {
        // TODO: Implement getPath() method.
    }

    #[Override] public function getQuery(): string
    {
        // TODO: Implement getQuery() method.
    }

    #[Override] public function getFragment(): string
    {
        // TODO: Implement getFragment() method.
    }

    #[Override] public function withScheme(string $scheme): UriInterface
    {
        // TODO: Implement withScheme() method.
    }

    #[Override] public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        // TODO: Implement withUserInfo() method.
    }

    #[Override] public function withHost(string $host): UriInterface
    {
        // TODO: Implement withHost() method.
    }

    #[Override] public function withPort(?int $port): UriInterface
    {
        // TODO: Implement withPort() method.
    }

    #[Override] public function withPath(string $path): UriInterface
    {
        // TODO: Implement withPath() method.
    }

    #[Override] public function withQuery(string $query): UriInterface
    {
        // TODO: Implement withQuery() method.
    }

    #[Override] public function withFragment(string $fragment): UriInterface
    {
        // TODO: Implement withFragment() method.
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }
}