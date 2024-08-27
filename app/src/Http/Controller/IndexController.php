<?php

namespace App\Http\Controller;

use Framework\Psr\Http\Message\ResponseInterface as Response;
use Framework\Psr\Http\Message\RequestInterface as Request;


class IndexController
{
    public function index(Request $request, Response $response): Response
    {
        echo 'controller';

        $response->getBody()->write('abc');

       // return $response;
    }

}