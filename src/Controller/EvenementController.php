<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementFormType;
use App\Form\EvenementSupprimerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="evenement")
     */
    public function index(): Response
    {
        $repo=$this->getDoctrine()->getRepository(Evenement::class);

        $evenement=$repo->findAll();

        return $this->render('evenement/index.html.twig', [
            "evenement"=>$evenement
        ]);
    }
    /**
     * @Route("/evenement/ajouter", name="evenement_ajouter")
     */

    public function ajouter(Request $request){

        //creer une evenement vide
        $evenement=new Evenement();

        //creation du formulaire
        $form=$this->createForm(EvenementFormType::class, $evenement);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récup de l'entityManager
            $em=$this->getDoctrine()->getManager();
            //dire au mananger qu'on veut garder notre objet en BDD
            $em->persist($evenement);
            //generer un flush
            $em->flush();

            return $this->redirectToRoute("evenement");

        }
        return $this->render("evenement/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);

    }

    /**
     * @Route("/evenement/modifier/{id}", name ="evenement_modifier")
     */
    public function modifier($id, Request $request){

        $repo=$this->getDoctrine()->getRepository(Evenement::class);
        $evenement=$repo->find($id);
        //creation du formulaire
        $form=$this->createForm(EvenementFormType::class, $evenement);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récup de l'entityManager
            $em=$this->getDoctrine()->getManager();
            //dire au manager qu'on veut garder notre objet en BDD
            $em->persist($evenement);
            //generer un flush
            $em->flush();

            return $this->redirectToRoute("evenement");

        }
        return $this->render("evenement/modifier.html.twig",[
            "formulaire"=>$form->createView(),
            "evenement"=>$evenement
        ]);

    }
    /**
     * @Route("/evenement/supprimer/{id}", name ="evenement_supprimer")
     */
    public function supprimer($id, Request $request){

        $repo=$this->getDoctrine()->getRepository(Evenement::class);
        $evenement=$repo->find($id);
        //creation du formulaire
        $form=$this->createForm(EvenementSupprimerType::class, $evenement);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récup de l'entityManager
            $em=$this->getDoctrine()->getManager();
            //dire au mananger qu'on veut garder notre objet en BDD
            $em->remove($evenement);
            //generer un flush
            $em->flush();

            return $this->redirectToRoute("evenement");

        }
        return $this->render("evenement/supprimer.html.twig",[
            "formulaire"=>$form->createView(),
            "evenement"=>$evenement
        ]);

    }
}
