<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Form\ReaderType;
use App\Form\ReaderBookType;
use App\Repository\ReaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReaderController extends AbstractController
{
    #[Route('/reader/show/{id}', name: 'reader_show_details')]
    public function show_detail(ReaderRepository $repository, $id) : Response
    {
        $reader = $repository->find($id);
        return $this->render('reader/show_reader_detail.html.twig',[
            'reader' => $reader,
        ]);
    }
    #[Route('/reader/get', name: 'app_reader_get')]
    public function show_details_authors(ReaderRepository $readerRepository): Response
    {
        $reader = $readerRepository->findAll();
        return $this->render('reader/show_details.html.twig', [
            'controller_name' => 'AuthorController',
            'reader' => $reader,
        ]);
    }
    #[Route('/reader/new', name: 'app_reader_new')]
    public function addStudent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReaderType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $reader = new Reader();
            $reader->setUsername($data->getUsername());
            $entityManager->persist($reader);
            $entityManager->flush();
            return $this->redirectToRoute('app_reader_get');
        }
        return $this->render('reader/add_reader.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    // add reader to book and book to reader function exist 1 time in readercontroller or bookcontroller to work with manytomany database relation
    #[Route('/reader/add_book_reader', name: 'app_reader_book_add')]
    public function addBookToReader(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReaderBookType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $reader = $data['reader'];
            $book = $data['book'];
            if ($reader && $book) {
                $reader->addBook($book);
                $book->addReader($reader);
                $entityManager->persist($reader);
                $entityManager->persist($book);
                $entityManager->flush();
            }
        }
        return $this->render('reader_book/reader_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}