<?php

namespace Framework\Psr7\Interfaces;

interface HeadersInterface
{
    public function addHeader($name, $value): HeadersInterface;

    public function removeHeader(string $name): HeadersInterface;

    public function getHeader(string $name, $default = []): array;

    public function setHeader($name, $value): HeadersInterface;

    public function setHeaders(array $headers): HeadersInterface;

    public function hasHeader(string $name): bool;

    public function getHeaders(bool $originalCase): array;
}