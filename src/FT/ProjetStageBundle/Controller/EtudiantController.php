<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 21/09/2017
 * Time: 16:21
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Entity\Etudiant;
use FT\ProjetStageBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EtudiantController extends Controller
{
    public function listeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listeEtudiants = $em->getRepository('FTProjetStageBundle:Etudiant')->findAll();

        return $this->render('FTProjetStageBundle:Etudiant:liste.html.twig', array(
            'listeEtudiants' => $listeEtudiants
        ));
    }

    public function mesEquipesProprietaireAction(Personne $personne)
    {
        $em = $this->getDoctrine()->getManager();
        $listeEquipes = $em->getRepository('FTProjetStageBundle:Equipe')->findBy(array(
            'proprietaire' => $personne->getId()
        ));

        return $this->render('FTProjetStageBundle:Etudiant:listeEquipesProprietaire.html.twig', array(
            'listeEquipes' => $listeEquipes,
        ));
    }

    public function mesEquipesAction(Etudiant $etudiant)
    {
        $listeEquipes = $etudiant->getEquipes();

        return $this->render('FTProjetStageBundle:Etudiant:listeEquipes.html.twig', array(
            'listeEquipes' => $listeEquipes
        ));
    }
}