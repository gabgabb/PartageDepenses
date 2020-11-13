<?php

namespace App\Controller;


use App\Entity\Depense;
use App\Entity\Evenement;
use App\Entity\Participants;
use App\Form\ParticipantsFormType;
use App\Form\ParticipantsModifierType;
use App\Form\ParticipantsSupprimerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantsController extends AbstractController
{
    /**
     * @Route("/participants/voir/{idEvenement}", name="participants")
     */
    public function index($idEvenement)
    {
        $repo=$this->getDoctrine()->getRepository(Evenement::class);
        $evenement=$repo->find($idEvenement);

        $participants=$evenement->getParticipants();

        return $this->render('participants/index.html.twig', [
            'participants'=>$participants,
            'idEvenement'=>$idEvenement,
            'evenement'=>$evenement,

        ]);
    }
    /**
     * @Route("/participants/voir/{idEvenement}/ajouter/", name="participants_ajouter")
     */

    public function ajouter($idEvenement,Request $request){

        //creer une chaton vide
        $participants=new Participants();


        //creation du formulaire
        $form=$this->createForm(ParticipantsFormType::class, $participants);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récup de l'entityManager
            $em=$this->getDoctrine()->getManager();
            //dire au mananger qu'on veut garder notre objet en BDD
            $em->persist($participants);
            //generer un flush
            $em->flush();
            return $this->redirectToRoute("participants", ["idEvenement"=>$participants->getEvenement()->getId()]);

        }
        return $this->render("participants/ajouter.html.twig",[
            'participants'=>$participants,
            "formulaire"=>$form->createView(),
            "idEvenement"=>$idEvenement,

        ]);

    }
    /**
     * @Route("/participants/voir/{idEvenement}/modifier/{id}", name ="participants_modifier")
     */
    public function modifier($idEvenement, $id, Request $request){

        $repo=$this->getDoctrine()->getRepository(Participants::class);
        $participant=$repo->find($id);
        //creation du formulaire
        $form=$this->createForm(ParticipantsModifierType::class, $participant);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récup de l'entityManager
            $em=$this->getDoctrine()->getManager();
            //dire au manager qu'on veut garder notre objet en BDD
            $em->persist($participant);
            //generer un flush
            $em->flush();

            return $this->redirectToRoute("participants", ["idEvenement" =>$participant->getEvenement()->getId()]);

        }
        return $this->render("participants/modifier.html.twig",[
            "formulaire"=>$form->createView(),
            "participants"=>$participant,
            "idEvenement"=>$idEvenement,

        ]);

    }
    /**
     * @Route("/participants/{idEvenement}/supprimer/{id}", name ="participants_supprimer")
     */
    public function supprimer($idEvenement,$id, Request $request){

        $repo=$this->getDoctrine()->getRepository(Participants::class);

        $participants=$repo->find($id);

        //creation du formulaire
        $form=$this->createForm(ParticipantsSupprimerType::class, $participants);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récup de l'entity Manager
            $em=$this->getDoctrine()->getManager();
            //dire au mananger qu'on veut garder notre objet en BDD
            $em->remove($participants);
            //generer un flush
            $em->flush();

            return $this->redirectToRoute("participants", ["idEvenement" => $participants->getEvenement()->getId()]);

        }
        return $this->render("participants/supprimer.html.twig",[
            "formulaire"=>$form->createView(),
            "participants"=>$participants,
            "idEvenement"=>$idEvenement,
        ]);

    }

    public function totalMontant(){
        $totalmontant=0;
        $em = $this->getDoctrine()->getManager();
        $depense = $em->getRepository(Depense::class)->findBy();

        foreach ($depense as $montant){
            $totalmontant+=$montant->getMontant();
        }
    return $totalmontant;
    }
}
