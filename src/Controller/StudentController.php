<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Form\ClassroomType;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class StudentController extends AbstractController
{
    #[Route('/student/get', name: 'app_student_get')]
    public function show_details_authors(StudentRepository $studentRepository): Response
    {
        $students = $studentRepository->findAll();
        return $this->render('student1/show_details.html.twig', [
            'controller_name' => 'AuthorController',
            'student' => $students,
        ]);
    }
    // add student
    #[Route('/student/new', name: 'app_student_new')]
    public function addAuthor(Request $request, EntityManagerInterface $entityManager): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class,$student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($student);
            $entityManager->persist($student);
            $entityManager->flush();
            return $this->redirectToRoute('app_student_get');
        }
        return $this->render('student1/add_student.html.twig', [
            'student' => $form->createView(),
        ]);
    }
    #[Route('/student/delete/{id}', name: 'student_delete')]
    public function delete_author($id,ManagerRegistry $registry): Response
    {
        $delete = $registry->getManager();
        if(!$id)
        {
            throw $this->createNotFoundException('No ID found');
        }
        $author = $delete->getRepository(Student::class)->find($id);
        if($author != null)
        {
            $delete->remove($author);
            $delete->flush();
        }
        return $this->redirectToRoute('app_student_get');
    }
    #[Route('/student/edit/{id}', name: 'app_student_edit')]
    public function edit(StudentRepository $repository, $id, Request $request , ManagerRegistry $registry) : Response
    {
        $book = $repository->find($id);
        $form = $this->createForm(StudentType::class,$book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $registry->getManager();
            $book = $form->getData();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("app_student_get");
        }
        return $this->render('student1/edit_student.html.twig', [ 'book' => $form->createView(), ]);
    }
}