<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22/09/2017
 * Time: 10:15
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Entity\Formateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FormateurController extends Controller
{
    public function listeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listeFormateurs = $em->getRepository('FTProjetStageBundle:Formateur')->findAll();

        return $this->render('FTProjetStageBundle:Formateur:liste.html.twig', array(
            'listeFormateurs' => $listeFormateurs
        ));
    }

    //Récupère tous les projets créés par le formateur en question
    public function mesProjetsProprietaireAction(Formateur $formateur)
    {
        $listeProjetsProprietaire = $formateur->getProjets();

        return $this->render('FTProjetStageBundle:Formateur:listeProjetsProprietaire.html.twig', array(
            'listeProjetsProprietaire' => $listeProjetsProprietaire
        ));
    }

    public function projetsFormateurAction(Formateur $formateur)
    {
        $listeProjets = $formateur->getProjets();

        return $this->render('FTProjetStageBundle:Formateur:listeProjets.html.twig', array(
            'listeProjets' => $listeProjets
        ));
    }
}