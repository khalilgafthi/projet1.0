<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\EditAuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    // display author table ASC
    #[Route('/author/getasc', name: 'app_author_get_asc')]
    public function show_details_authors_asc(AuthorRepository $authorRepository): Response
    {
       $authors = $authorRepository->listauthorbymail();
        return $this->render('author/show_details.html.twig', [
            'author' => $authors,
        ]);
    }
    // add function
    #[Route('/author/new', name: 'app_author_new')]
    public function addAuthor(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(AuthorType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $author = new Author();
            $author->setUsername($data['username']);
            $author->setEmail($data['email']);
            $author->setNbBooks($data['nb_books']);
            $author->setPicture($data['picture']);
            $entityManager->persist($author);
            $entityManager->flush();
            return $this->redirectToRoute('app_author_get');
        }
        return $this->render('author/add_author.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // display authors  from database inside an array using findAll method from class Author Repository inside Repository class
    #[Route('/author/get', name: 'app_author_get')]
    public function show_details_authors(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findBy(array(),array('username'=>'ASC'));
        return $this->render('author/show_details.html.twig', [
            'controller_name' => 'AuthorController',
            'author' => $authors,
        ]);
    }
    /* or use
public function show_details_authors(): Response
    {
        $authorRepository = $this->getDoctrine()->getRepository(Author::class);
        $authors = $authorRepository->findAll();
        return $this->render('author/show_details.html.twig', [
            'controller_name' => 'AuthorController',
            'author' => $authors,
        ]);
    }
     kif theb tna7i getDoctrine st3ml managerregistry */
    //delete function
    #[Route('/author/delete/{id}', name: 'author_delete')]
    public function delete_author($id,ManagerRegistry $registry): Response
    {
        if(!$id)
        {
            throw $this->createNotFoundException('No ID found');
        }
        $author = $registry->getRepository(Author::class)->find($id);
        if($author != null)
        {
            $author->remove($author);
            $author->flush();
        }
        return $this->redirectToRoute('app_author_get');
    }
    /*
#[Route('/author/delete/{id}', name: 'author_delete')]
public function deleteAuthor($id, EntityManagerInterface $entityManager): Response
{
    if (!$id) {
        throw $this->createNotFoundException('No ID found');
    }
    $author = $entityManager->getRepository(Author::class)->find($id);
    if (!$author) {
        throw $this->createNotFoundException('Author not found');
    }
    $entityManager->remove($author);
    $entityManager->flush();
    return $this->redirectToRoute('app_author_get');
}
     */
    /*
      public function delete_author($id): Response
    {
        $delete = $this->getDoctrine()->getManager();
        if(!$id)
        {
            throw $this->createNotFoundException('No ID found');
        }
        $author = $delete->getRepository(Author::class)->find($id);
        if($author != null)
        {
            $delete->remove($author);
            $delete->flush();
        }
        return $this->redirectToRoute('app_author_get');
    }
     */
    // edit author function
    #[Route('/author/edit/{id}', name: 'author_edit')]
    public function edit(AuthorRepository $repository, $id, Request $request , ManagerRegistry $registry) : Response
    {
        $author = $repository->find($id);
        $form = $this->createForm(EditAuthorType::class,$author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $registry->getManager();
            $author = $form->getData();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute("app_author_get");
        }
        return $this->render('author/edit_author.html.twig', [ 'f' => $form->createView(), ]);
    }
}