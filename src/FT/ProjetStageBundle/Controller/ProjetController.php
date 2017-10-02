<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22/09/2017
 * Time: 10:28
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjetController extends Controller
{
    public function listeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listeProjets = $em->getRepository('FTProjetStageBundle:Projet')->findAll();

        return $this->render('FTProjetStageBundle:Projet:listePersonnes.html.twig', array(
            'listeProjets' => $listeProjets
        ));
    }

    public function equipesParticipantesAction(Projet $projet)
    {
        //var_dump($projet);
        $em = $this->getDoctrine()->getManager();
        $listeEquipes = $em->getRepository('FTProjetStageBundle:Equipe')->getEquipesParticipantes($projet->getId());
        //var_dump($listeEquipes);

        return $this->render('FTProjetStageBundle:Equipe:equipesParticipantes.html.twig', array(
            'projet' => $projet,
            'listeEquipes' => $listeEquipes
        ));
    }
}