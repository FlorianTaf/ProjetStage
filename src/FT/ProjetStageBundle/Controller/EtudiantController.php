<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 21/09/2017
 * Time: 16:21
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Entity\Equipe;
use FT\ProjetStageBundle\Entity\Personne;
use FT\ProjetStageBundle\Form\EquipeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

    public function mesEquipesAction(Personne $personne)
    {
        $em = $this->getDoctrine()->getManager();
        $listeEquipes = $em->getRepository('FTProjetStageBundle:Equipe')->findBy(array(
            'idProprietaire' => $personne->getEtudiant()
        ));

        return $this->render('FTProjetStageBundle:Etudiant:listeEquipes.html.twig', array(
            'personne' => $personne,
            'listeEquipes' => $listeEquipes,
        ));
    }

    public function creerEquipeAction(Request $request)
    {
        $personne = $this->getUser();
        $equipe = new Equipe();

        $form = $this->createForm(EquipeType::class, $equipe);

        if ($request->isMethod('POST')) {
            var_dump($personne);
            if ($form->handleRequest($request)->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $equipe->setDateCreation(new \DateTime());
                $equipe->setProprietaire($personne->getEtudiant());

                $em->persist($equipe);
                $em->flush();

                return $this->redirectToRoute('ft_personne_dashboard', array(
                    'personne' => $personne
                ));
            }
        }

        return $this->render('FTProjetStageBundle:Etudiant:creerProjet.html.twig', array(
            'form' => $form->createView(),
            'personne' => $personne
        ));
    }
}