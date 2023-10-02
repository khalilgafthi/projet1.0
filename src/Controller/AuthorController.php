<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author/{name}', name: 'app_author')]
    public function showAuthor($name): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
            'name' => $name,
        ]);
    }
}
