<?php
/**
 * Created by PhpStorm.
 * Personne: Florian
 * Date: 15/09/2017
 * Time: 14:45
 */

namespace FT\ProjetStageBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $lastProjets = $em->getRepository('FTProjetStageBundle:Projet')->getLastThree();
        $lastEquipes = $em->getRepository('FTProjetStageBundle:Equipe')->getLastThree();

        return $this->render('FTProjetStageBundle::index.html.twig', array(
            'listeProjets' => $lastProjets,
            'listeEquipes' => $lastEquipes
        ));
    }
}