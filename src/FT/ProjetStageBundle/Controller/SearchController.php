<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 27/09/2017
 * Time: 12:08
 */

namespace FT\ProjetStageBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function searchAction(Request $request)
    {
        return $this->render('FTProjetStageBundle:Search:search.html.twig');
    }

    public function searchPersonneAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //On détermine quel champ on va vérifier dans la BDD
        $typeRecherche = $request->request->get('personne');

        $recherche = $request->request->get('findPersonne');

        $personnes = null;

        if ($typeRecherche === "nom") {
            $personnes = $em->getRepository('FTProjetStageBundle:Personne')->getPersonneLikeNom($recherche);
        }
        if ($typeRecherche === "email") {
            $personnes = $em->getRepository('FTProjetStageBundle:Personne')->getPersonneLikeMail($recherche);
        }
        if ($typeRecherche === "email") {
            $personnes = $em->getRepository('FTProjetStageBundle:Personne')->getPersonneLikeNomOrMail($recherche);
        }

        return $this->render('FTProjetStageBundle:Search:search.html.twig', array(
            'personnes' => $personnes
        ));
    }

    public function searchProjetAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //On détermine quel champ on va vérifier dans la BDD
        $typeRecherche = $request->request->get('projet');

        $recherche = $request->request->get('findProjet');

        $projets = null;

        if ($typeRecherche === "titre") {
            $projets = $em->getRepository('FTProjetStageBundle:Projet')->getProjetLikeTitre($recherche);
        }
        if ($typeRecherche === "sujet") {
            $projets = $em->getRepository('FTProjetStageBundle:Projet')->getProjetLikeSujet($recherche);
        }

        return $this->render('FTProjetStageBundle:Search:search.html.twig', array(
            'projets' => $projets
        ));
    }
}