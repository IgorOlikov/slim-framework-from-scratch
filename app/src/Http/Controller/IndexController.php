<?php

namespace App\Http\Controller;

use Framework\Psr\Http\Message\ResponseInterface;

class IndexController
{
    public function index(): ResponseInterface
    {
        phpinfo();
    }

}