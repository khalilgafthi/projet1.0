<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\BookType;
use App\Form\ClassroomType;
use App\Form\EditBookType;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Classroom;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    #[Route('/classroom/get', name: 'app_classroom_get')]
    public function show_details_authors(ClassroomRepository $studentRepository): Response
    {
        $students = $studentRepository->findAll();
        return $this->render('classroom/show_details.html.twig', [
            'controller_name' => 'AuthorController',
            'classroom' => $students,
        ]);
    }
    // add student
    #[Route('/classroom/new', name: 'app_classroom_new')]
    public function addStudent(Request $request, ManagerRegistry $entityManager): Response
    {
        $em = $entityManager->getManager();
        $form = $this->createForm(ClassroomType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $classroom = new Classroom();
            $classroom->setName($data->getName());
            $classroom->setNbTable($data->getNbTable());
            $em->persist($classroom);
            $em->flush();
            return $this->redirectToRoute('app_classroom_get');
        }
        return $this->render('classroom/add_classroom.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/classroom/delete/{id}', name: 'classroom_delete')]
    public function delete_author($id,ManagerRegistry $registry): Response
    {
        $delete = $registry->getManager();
        if(!$id)
        {
            throw $this->createNotFoundException('No ID found');
        }
        $author = $delete->getRepository(Classroom::class)->find($id);
        if($author != null)
        {
            $delete->remove($author);
            $delete->flush();
        }
        return $this->redirectToRoute('app_classroom_get');
    }
    #[Route('/classroom/edit/{id}', name: 'app_classroom_edit')]
    public function edit(ClassroomRepository $repository, $id, Request $request , ManagerRegistry $registry) : Response
    {
        $book = $repository->find($id);
        $form = $this->createForm(CLassroomType::class,$book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $registry->getManager();
            $book = $form->getData();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("app_classroom_get");
        }
        return $this->render('classroom/classroom/edit_classroom.html.twig', [ 'book' => $form->createView(), ]);
    }
}