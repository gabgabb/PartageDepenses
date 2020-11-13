<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Entity\Participants;
use App\Form\DepenseModifierType;
use App\Form\DepenseSupprimerType;
use App\Form\DepenseFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DepenseController extends AbstractController
{
    /**
     * @Route("/depense/voir/{idEvenement}/{idParticipants}", name="depense")
     */
    public function index($idParticipants,$idEvenement)
    {
        $repo=$this->getDoctrine()->getRepository(Participants::class);
        $participants=$repo->find($idParticipants);

        $depense=$participants->getDepense();

        return $this->render('depense/index.html.twig', [
            'depense'=>$depense,
            'idEvenement'=>$idEvenement,
            'idParticipants'=>$idParticipants,
            'participants'=>$participants,

        ]);
    }
    /**
     * @Route("/depense/voir/{idEvenement}/{idParticipants}/ajouter", name="depense_ajouter")
     */

    public function ajouter($idEvenement,$idParticipants, Request $request){

        //creer une chaton vide
        $depense=new Depense();

        //creation du formulaire
        $form=$this->createForm(DepenseFormType::class, $depense);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récup de l'entityManager
            $em=$this->getDoctrine()->getManager();
            //dire au mananger qu'on veut garder notre objet en BDD
            $em->persist($depense);
            //generer un flush
            $em->flush();

            //aller à la liste des participants
            return $this->redirectToRoute("depense", array("idParticipants"=>$depense->getParticipants()->getId(),"idEvenement"=>$depense->getParticipants()->getEvenement()->getId()));

        }
        return $this->render("depense/ajouter.html.twig",[
            "formulaire"=>$form->createView(),
            "idEvenement"=>$idEvenement,
            "idParticipants"=>$idParticipants,

        ]);

    }
    /**
     * @Route("/depense/voir/{idEvenement}/{idParticipants}/modifier/{id}", name ="depense_modifier")
     */
    public function modifier($idEvenement, $idParticipants, $id, Request $request){

        $repo=$this->getDoctrine()->getRepository(Depense::class);
        $depense=$repo->find($id);
        //creation du formulaire
        $form=$this->createForm(DepenseModifierType::class, $depense);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récup de l'entityManager
            $em=$this->getDoctrine()->getManager();
            //dire au manager qu'on veut garder notre objet en BDD
            $em->persist($depense);
            //generer un flush
            $em->flush();

            return $this->redirectToRoute("depense", array("idParticipants"=>$depense->getParticipants()->getId(),"idEvenement"=>$depense->getParticipants()->getEvenement()->getId()));

        }
        return $this->render("depense/modifier.html.twig",[
            "formulaire"=>$form->createView(),
            "depense"=>$depense,
            "idEvenement"=>$idEvenement,
            "idParticipants"=>$idParticipants,
        ]);

    }
    /**
     * @Route("/depense/{idEvenement}/{idParticipants}/supprimer/{id}", name ="depense_supprimer")
     */
    public function supprimer($idEvenement, $idParticipants, $id, Request $request){

        $repo=$this->getDoctrine()->getRepository(Depense::class);
        $depense=$repo->find($id);
        //creation du formulaire
        $form=$this->createForm(DepenseSupprimerType::class, $depense);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récup de l'entity Manager
            $em=$this->getDoctrine()->getManager();
            //dire au mananger qu'on veut garder notre objet en BDD
            $em->remove($depense);
            //generer un flush
            $em->flush();

            //aller à la liste des participants
            return $this->redirectToRoute("depense", array("idParticipants"=>$depense->getParticipants()->getId(),"idEvenement"=>$depense->getParticipants()->getEvenement()->getId()));

        }
        return $this->render("depense/supprimer.html.twig",[
            "formulaire"=>$form->createView(),
            "depense"=>$depense,
            "idEvenement"=>$idEvenement,
            "idParticipants"=>$idParticipants,
        ]);

    }
}
