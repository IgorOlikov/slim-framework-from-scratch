<?php

namespace App\Http\Controller;

use App\Http\Services\Interfaces\ServiceInterface;
use App\Http\Services\TestService;
use Framework\Psr\Http\Message\ResponseInterface as Response;
use Framework\Psr\Http\Message\RequestInterface as Request;


class IndexController
{

    protected ServiceInterface $testService;

    public function __construct(ServiceInterface $testService)
    {
        $this->testService = $testService;
    }

    public function index(Request $request, Response $response): Response
    {
        $response->getBody()->write($this->testService->getFullName());

        return $response;
    }

}