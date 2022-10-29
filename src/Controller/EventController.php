<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
    #[Route('/events', name: 'app_events')]
    public function listevent(EventRepository $repository){

        $event=$repository->findAll();
        return $this->render("event/listevent.html.twig",array("tabevents"=>$event));

    }
    #[Route('/addevent', name: 'app_addevent')]
    public function addClub(ManagerRegistry $doctrine,Request $request)
    {
        $event= new Event();
        $form=$this->createForm(EventType::class,$event);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em =$doctrine->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute("app_events");
        }
        return $this->renderForm("event/addevent.html.twig",array("formEvent"=>$form));
    }
    #[Route('/update/{id}', name: 'app_updatevent')]
    public function update(EventRepository $repository,$id,ManagerRegistry $doctrine,Request $request){

        $event=$repository->find($id);
        $form=$this->createForm(EventType::class,$event);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em =$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("app_events");
        }
        return $this->renderForm("club/addevent.html.twig",
            array("formEvent"=>$form));

    }
}
