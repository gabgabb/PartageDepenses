<?php

namespace App\Controller;


use App\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    public function menu()
    {
        $repository=$this->getDoctrine()->getRepository(Evenement::class);
        $evenement=$repository->findAll();

        return $this->render('menu/_menu.html.twig', [
            "evenement"=>$evenement
        ]);
    }
}
