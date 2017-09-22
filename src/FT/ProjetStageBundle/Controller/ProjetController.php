<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22/09/2017
 * Time: 10:28
 */

namespace FT\ProjetStageBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjetController extends Controller
{
    public function listeAction()
    {
        $personne = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $listeProjets = $em->getRepository('FTProjetStageBundle:Projet')->findAll();

        return $this->render('FTProjetStageBundle:Projet:liste.html.twig', array(
            'personne' => $personne,
            'listeProjets' => $listeProjets
        ));
    }
}