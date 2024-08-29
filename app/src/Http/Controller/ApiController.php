<?php

namespace App\Http\Controller;

use Framework\Psr\Http\Message\RequestInterface as Request;
use Framework\Psr\Http\Message\ResponseInterface as Response;

class ApiController
{

    public function index(Request $request, Response $response): Response
    {

        $response->getBody()->write('api-controller');


        return $response;
    }

}