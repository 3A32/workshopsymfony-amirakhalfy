<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    #[Route('/Affiche', name: 'affichemesg')]
    public function Affiche(){
        return new Response("Bonjour mes etudiants");

    }
    #[Route('/AfficheName/{name}', name: 'affichename')]
    Public function AfficherName($name){
    return $this->render('Student/affiche.html.twig',['n'=>$name]);

    }
}
