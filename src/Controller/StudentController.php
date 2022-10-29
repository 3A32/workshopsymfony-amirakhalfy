<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\SearchstudentType;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/Liststudent', name: 'app_Liststudent')]
    public function Liststudent(StudentRepository $repository){
        $student=$repository->findAll();
        return $this->render("student/liststudent.html.twig",array("tabstudent"=>$student));

    }
    #[Route('/addstudent', name: 'app_addstudent')]
    public function addstudent(ManagerRegistry $doctrine,Request $request)
    {

        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($student);
            $em->flush();
            return $this->redirectToRoute('app_Liststudent');
        }
        return $this->renderForm("student/addstudent.html.twig", array("formStudent" => $form));
    }
    #[Route('/updatestudent/{id}', name: 'app_updatestudent')]
    public function updatestudent(ManagerRegistry $doctrine,Request $request,StudentRepository $repository,$id){

        $student=$repository->find($id);
        $form=$this->createForm(StudentType::class,$student);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_Liststudent');
        }
        return $this->renderForm("student/updatestudent.html.twig",array("formStudent"=>$form));

    }
    #[Route('/deletestudent{id}', name: 'app_deletestudent')]
    public function deletestudent(StudentRepository $repository,$id,ManagerRegistry $doctrine){
        $student=$repository->find($id);
        $em=$doctrine->getManager();
        $em->remove($student);
        $em->flush();
        return $this->redirectToRoute("app_Liststudent");
    }
    #[Route('/liist', name: 'liistapp')]
    public function liist(StudentRepository $repository,Request $request){

        $students= $repository->findAll();
        $studentsByNce=$repository->getStudentsOrdredByNCE();
        $formSearch= $this->createForm(SearchstudentType::class);
        $formSearch->handleRequest($request) ;
        $topStudents= $repository->topStudent();
        if($formSearch->isSubmitted()){
            $nsc=$formSearch->getData();
            $result= $repository->findStudentByNCE($nsc);
            return $this->renderForm("student/liist.html.twig",
                array("students"=>$result,"byNCE"=>$studentsByNce,"searchForm"=>$formSearch,"topStudents"=>$topStudents));
        }
        return $this->renderForm("student/liist.html.twig",
            array("students"=>$students,
                "byNCE"=>$studentsByNce,
                "searchForm"=>$formSearch,
                "topStudents"=>$topStudents ));

    }


}
