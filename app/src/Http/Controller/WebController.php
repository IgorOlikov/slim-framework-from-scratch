<?php

namespace App\Http\Controller;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Framework\Psr\Http\Message\ResponseInterface as Response;
use Framework\Psr\Http\Message\RequestInterface as Request;
use Twig\Environment;


class WebController
{
    /**
     * @param EntityManager $entityManager
     * @param Environment $view
     */
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected Environment $view
    )
    {
    }

    public function index(Request $request, Response $response): Response
    {






        //$conn = $this->entityManager->getConnection();
        //dd($conn);

        //dd($this->entityManager->getRepository());

        return $response;
    }

}