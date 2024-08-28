<?php

namespace App\Http\Controller;

use App\Http\Services\Interfaces\ServiceInterface;
use App\Http\Services\TestService;
use Doctrine\ORM\EntityManagerInterface;
use Framework\Psr\Http\Message\ResponseInterface as Response;
use Framework\Psr\Http\Message\RequestInterface as Request;


class IndexController
{


    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    public function index(Request $request, Response $response): Response
    {
        $conn = $this->entityManager->getConnection();

        dd($conn);

        return $response;
    }

}