<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22/09/2017
 * Time: 10:39
 */

namespace FT\ProjetStageBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EquipeController extends Controller
{
    public function listeAction()
    {
        $personne = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $listeEquipes = $em->getRepository('FTProjetStageBundle:Equipe')->findAll();

        return $this->render('FTProjetStageBundle:Equipe:liste.html.twig', array(
            'personne' => $personne,
            'listeEquipes' => $listeEquipes
        ));
    }
}