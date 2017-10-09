<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22/09/2017
 * Time: 10:15
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Entity\Personne;
use FT\ProjetStageBundle\Entity\Projet;
use FT\ProjetStageBundle\Form\ProjetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

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
    public function mesProjetsProprietaireAction(Personne $personne)
    {
        $em = $this->getDoctrine()->getManager();
        $listeProjetsProprietaire = $em->getRepository('FTProjetStageBundle:Projet')->findBy(array(
            'proprietaire' => $personne->getId()
        ));

        return $this->render('FTProjetStageBundle:Formateur:listeProjetsProprietaire.html.twig', array(
            'listeProjetsProprietaire' => $listeProjetsProprietaire
        ));
    }
}