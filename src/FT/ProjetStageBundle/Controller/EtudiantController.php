<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 21/09/2017
 * Time: 16:21
 */

namespace FT\ProjetStageBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EtudiantController extends Controller
{
    public function listeAction()
    {
        $personne = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $listeEtudiants = $em->getRepository('FTProjetStageBundle:Personne')->loadEtudiants();

        return $this->render('FTProjetStageBundle:Etudiant:liste.html.twig', array(
            'personne' => $personne,
            'listeEtudiants' => $listeEtudiants
        ));
    }
}