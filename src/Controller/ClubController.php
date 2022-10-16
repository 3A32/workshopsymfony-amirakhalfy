<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClubController extends AbstractController
{
    #[Route('/club', name: 'app_club')]
    public function index(): Response
    {
        return $this->render('club/index.html.twig', [
            'controller_name' => 'ClubController',
        ]);
    }

    #[Route('/club/getName/{name}', name: 'getName')]
    public function getName($name): Response
    {
        return $this->render('club/detail.html.twig', ['nom' => $name]);
    }
    #[Route('/List', name: 'listformation')]
    public function List(): Response
    {
        $var1= "3A32";
        $var2= "J12";
        $formations = array(
            array('ref' => 'form147', 'Titre' => 'Formation Symfony
4', 'Description' => 'formation theorique',
                'date_debut' => '12/06/2020', 'date_fin' => '19/06/2020',
                'nb_participants' => 19),
            array('ref' => 'form177', 'Titre' => 'Formation SOA',
                'Description' => 'formation theorique', 'date_debut' => '03/12/2020', 'date_fin' => '10/12/2020',
                'nb_participants' => 0),
            array('ref' => 'form178', 'Titre' => 'Formation Angular',
                'Description' => 'formation pratique', 'date_debut' => '10/06/2020', 'date_fin' => '14/06/2020',
                'nb_participants' => 22));
        return $this->render("club/list.html.twig",
            array("x"=>$var1,"y"=>$var2,"tabFormation"=>$formations));
    }
    #[Route('/participer/{id}', name: 'detail')]
    public function reservation($id)
    {
        return $this->render("club/detail.html.twig",['ref'=>$id]);
    }
    #[Route('/clubs', name: 'app_clubs')]
    public function listClub(ClubRepository $repository){

$clubs=$repository->findAll();
return $this->render("club/listClub.html.twig",array("tabclubs"=>$clubs));

    }
    #[Route('/addClub', name: 'app_addClub')]
    public function addClub(ManagerRegistry $doctrine,\Symfony\Component\HttpFoundation\Request $request)
    {
        $club= new Club();
        $form=$this->createForm(ClubType::class,$club);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em =$doctrine->getManager();
            $em->persist($club);
            $em->flush();
            return $this->redirectToRoute("app_clubs");
        }
        return $this->renderForm("club/addClub.html.twig",array("formClub"=>$form));
    }
    #[Route('/update/{id}', name: 'app_updateClub')]
    public function update(ClubRepository $repository,$id,ManagerRegistry $doctrine,\Symfony\Component\HttpFoundation\Request $request){

        $club=$repository->find($id);
        $form=$this->createForm(ClubType::class,$club);
        $form->handleRequest($request);
        if($form->isSubmitted()){
        $em =$doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute("app_clubs");
    }
        return $this->renderForm("club/addClub.html.twig",
            array("formClub"=>$form));

}
    #[Route('/delete/{id}', name: 'app_deleteClub')]
public function deleteClub(ClubRepository $repository,$id,ManagerRegistry $doctrine){
 $club=$repository->find($id);
 $em=$doctrine->getManager();
 $em->remove($club);
 $em->flush();
return $this->redirectToRoute("app_clubs");

}

}
