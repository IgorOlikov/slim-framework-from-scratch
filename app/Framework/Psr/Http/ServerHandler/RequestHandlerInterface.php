<?php

namespace Framework\Psr\Http\ServerHandler;

use Framework\Psr\Http\Message\ResponseInterface;
use Framework\Psr\Http\Message\ServerRequestInterface;

interface RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface;
}