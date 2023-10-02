<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_authors_list')]
    public function index(): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
            'authors' => $authors,
        ]);
    }
    #[Route('/student/{id}', name: 'app_details')]
    public function authorDetails($id): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        $author = null;
        foreach ($authors as $a) { // l id hdhika hia variable manuel eli mwjouda fel student/id url eli bch tsir gene bel route hhh winner maak //
            if ($a['id'] == $id) {
                $author = $a; // fel step hadhi author bch thez l a instance feha l data mta3 id mtaa3 author hdhka ;) //
                break;
            }
        }
        return $this->render('student/showAuthor.html.twig', [
            'controller_name' => 'StudentController',
            'author' => $author,
        ]);
    }
}
