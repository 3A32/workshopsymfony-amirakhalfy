<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Doctrine\Persistence\Event\ManagerEventArgs;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Test\Constraint\RequestAttributeValueSame;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    #[Route('/Listclassroom', name: 'app_Listclassroom')]
    public function ListClassroom(ClassroomRepository $repository){
        $classroom=$repository->findAll();
        return $this->render("classroom/listclassroom.html.twig",array("tabclassroom"=>$classroom));

    }
    #[Route('/addclassroom', name: 'app_addclassroom')]
    public function addclassroom(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request){

        $classroom=new Classroom();
        $form=$this->createForm(ClassroomType::class,$classroom);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->persist($classroom);
            $em->flush();
            return $this->redirectToRoute('app_Listclassroom');
        }
        return $this->renderForm("Classroom/addclassroom.html.twig",
            array("formClassroom"=>$form));

    }
    #[Route('/updateclassroom/{id}', name: 'app_updateclassroom')]
    public function updateclassroom(\Doctrine\Persistence\ManagerRegistry $doctrine,Request $request,ClassroomRepository $repository,$id){

        $classroom=$repository->find($id);
        $form=$this->createForm(ClassroomType::class,$classroom);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_Listclassroom');
        }
        return $this->renderForm("Classroom/updateclassroom.html.twig",array("formClassroom"=>$form));

    }



    #[Route('/deleteclassroom/{id}', name: 'app_deleteclassroom')]
    public function deleteclassroom(ClassroomRepository $repository,$id,\Doctrine\Persistence\ManagerRegistry $doctrine){
        $classroom=$repository->find($id);
        $em=$doctrine->getManager();
        $em->remove($classroom);
        $em->flush();
        return $this->redirectToRoute("app_Listclassroom");
    }

}
