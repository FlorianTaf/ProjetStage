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
        $em = $this->getDoctrine()->getManager();
        $listeEtudiants = $em->getRepository('FTProjetStageBundle:Etudiant')->findAll();

        return $this->render('FTProjetStageBundle:Etudiant:listePersonnes.html.twig', array(
            'listeEtudiants' => $listeEtudiants
        ));
    }

    public function mesEquipesAction(Personne $personne)
    {
        $em = $this->getDoctrine()->getManager();
        $listeEquipes = $em->getRepository('FTProjetStageBundle:Equipe')->findBy(array(
            'proprietaire' => $personne->getId()
        ));

        return $this->render('FTProjetStageBundle:Etudiant:listeEquipes.html.twig', array(
            'listeEquipes' => $listeEquipes,
        ));
    }

    public function creerEquipeAction(Request $request)
    {
        $personne = $this->getUser();
        $equipe = new Equipe();
        $em = $this->getDoctrine()->getManager();

        $membresWithoutUser = $em->getRepository('FTProjetStageBundle:Etudiant')->getEtudiantWithouUser($personne->getUSername());

        $form = $this->createForm(EquipeType::class, $equipe, array('usernames' => $membresWithoutUser));

        if ($request->isMethod('POST')) {
            if ($form->handleRequest($request)->isValid()) {

                $equipe->setDateCreation(new \DateTime());
                $equipe->setProprietaire($personne);

                $em->persist($equipe);
                $em->flush();

                return $this->redirectToRoute('ft_personne_dashboard');
            }
        }

        return $this->render('FTProjetStageBundle:Etudiant:creerEquipe.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}