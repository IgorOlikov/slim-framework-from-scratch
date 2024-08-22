<?php

namespace Framework\Core\Interfaces;

use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;
use Framework\Psr\Http\ServerMiddleware\MiddlewareInterface;

interface RouteInterface
{
    public function getInvocationStrategy(): InvocationStrategyInterface;

    public function setInvocationStrategy(InvocationStrategyInterface $invocationStrategy): RouteInterface;

    public function getMethods(): array;

    public function getPattern(): string;

    public function setPattern(string $pattern): RouteInterface;

    public function getCallable();

    public function setCallable($callable): RouteInterface;

    public function getName(): ?string;

    public function setName(string $name): RouteInterface;

    public function getIdentifier(): string;

    public function getArgument(string $name, ?string $default = null): ?string;

    public function getArguments(): array;

    public function setArgument(string $name, string $value): RouteInterface;

    public function setArguments(array $arguments): self;

    public function add($middleware): self;

    public function addMiddleware(MiddlewareInterface $middleware): self;

    public function prepare(array $arguments): self;

    public function run(ServerRequestInterface $request): ResponseInterface;
}