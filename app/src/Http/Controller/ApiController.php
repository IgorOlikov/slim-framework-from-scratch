<?php

namespace App\Http\Controller;

use App\Http\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Framework\Psr\Http\Message\RequestInterface as Request;
use Framework\Psr\Http\Message\ResponseInterface as Response;

class ApiController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function index(Request $request, Response $response): Response
    {

        //$userRepository = $this->entityManager->getRepository(User::class);

        $user = (new User())->setName('egorka');

        $this->entityManager->persist($user);

        $this->entityManager->flush();


        $response->getBody()->write('User' . ' ' . $user->getName() . ' ' . 'saved!');


        return $response;
    }

}