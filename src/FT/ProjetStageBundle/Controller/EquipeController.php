<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22/09/2017
 * Time: 10:39
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Entity\Equipe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EquipeController extends Controller
{
    public function listeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listeEquipes = $em->getRepository('FTProjetStageBundle:Equipe')->findAll();

        return $this->render('FTProjetStageBundle:Equipe:liste.html.twig', array(
            'listeEquipes' => $listeEquipes
        ));
    }

    public function equipeEtudiantsAction(Equipe $equipe)
    {
        $listeMembres = $equipe->getEtudiants();

        return $this->render('FTProjetStageBundle:Equipe:listeMembres.html.twig', array(
            'equipe' => $equipe,
            'listeMembres' => $listeMembres
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