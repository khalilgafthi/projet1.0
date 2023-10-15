<?php

namespace App\Controller;
use App\Entity\Book;
use App\Form\BookType;
use App\Form\EditBookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class BookController extends AbstractController
{
    #[Route('/book/show/{id}', name: 'book_show')]
    public function show_detail(BookRepository $repository, $id) : Response
    {
        $book = $repository->find($id);
        return $this->render('book/show_book_detail.html.twig',[
            'book' => $book,
        ]);
    }
    // display books in array
    #[Route('/book/get', name: 'app_book_get')]
    public function showDetailsBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findBy(['published' => 1]);
            $book_not_published = $bookRepository->findBy(['published' => 0]);
        return $this->render('book/show_book.html.twig', [
            'book' => $books,
            'booknot' => $book_not_published,
        ]);
    }
    #[Route('/book/new', name: 'app_book_new')]
    public function addAuthor(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            $author->addNbBooks();
            $entityManager->persist($book);
            $entityManager->persist($author);
            $entityManager->flush();
            return $this->redirectToRoute('app_book_get');
        }
        return $this->render('book/add_book.html.twig', [
            'book' => $form->createView(),
        ]);
    }
    // edit book function
    #[Route('/book/edit/{id}', name: 'book_edit')]
    public function edit(BookRepository $repository, $id, Request $request , ManagerRegistry $registry) : Response
    {
        $book = $repository->find($id);
        $form = $this->createForm(EditBookType::class,$book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $registry->getManager();
            $book = $form->getData();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("app_book_get");
        }
        return $this->render('book/edit_book.html.twig', [ 'book' => $form->createView(), ]);
    }
    // delete book function
    #[Route('/book/delete/{id}', name: 'book_delete')]
    public function delete_book($id,ManagerRegistry $registry): Response
    {
        $delete = $registry->getManager();
        if(!$id)
        {
            throw $this->createNotFoundException('No ID found');
        }
        $book = $delete->getRepository(Book::class)->find($id);
        if($book != null)
        {
            $delete->remove($book);
            $delete->flush();
        }
        return $this->redirectToRoute('app_book_get');
    }
}