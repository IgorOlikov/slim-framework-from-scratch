<?php

namespace App\Http\Controller;

use App\Http\Services\Interfaces\ServiceInterface;
use App\Http\Services\TestService;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Framework\Psr\Http\Message\ResponseInterface as Response;
use Framework\Psr\Http\Message\RequestInterface as Request;
use Twig\Environment;


class IndexController
{


    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected Environment $view
    )
    {
    }

    public function index(Request $request, Response $response): Response
    {

       dd($this->view);




        //$conn = $this->entityManager->getConnection();
        //dd($conn);

        //dd($this->entityManager->getRepository());

        return $response;
    }

}