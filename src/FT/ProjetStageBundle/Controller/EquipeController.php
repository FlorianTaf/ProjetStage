<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22/09/2017
 * Time: 10:39
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Entity\Equipe;
use FT\ProjetStageBundle\Entity\Etudiant;
use FT\ProjetStageBundle\Form\EquipeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        //Pour pouvoir ajouter tous les étudiants sauf soi-même
        $membresWithoutUser = $em->getRepository('FTProjetStageBundle:Etudiant')->getEtudiantWithouUser($personne->getUSername());

        $form = $this->createForm(EquipeType::class, $equipe, array('usernames' => $membresWithoutUser));

        if ($request->isMethod('POST')) {
            if ($form->handleRequest($request)->isValid()) {
                $listeEtudiants = $equipe->getEtudiants();
                $equipe->setDateCreation(new \DateTime());
                $equipe->setProprietaire($personne);

                $listeProjets = $form->get('projets')->getData();
                foreach ($listeEtudiants as $etudiant) {
                    foreach ($listeProjets as $projet) {
                        if ($etudiant->getSessionFormation() != $projet->getSessionFormation()) {
                            $errorEtudiant = new FormError("Erreur, un ou plusieurs étudiants ne participent pas à la même session de formation");
                            $form->get('etudiants')->addError($errorEtudiant);
                            return $this->render('FTProjetStageBundle:Etudiant:creerEquipe.html.twig',
                                array('form' => $form->createView()));
                        }
                    }
                }
                //var_dump($projet->getTitre());

                $em->persist($equipe);
                $em->flush();

                foreach ($listeEtudiants as $etudiant) {
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Inscription dans un groupe')
                        ->setFrom($personne->getEmail())
                        ->setTo($etudiant->getEmail())
                        ->setBody('Bonjour ! Je viens de t\'ajouter dans mon groupe !');
                    $this->container->get('mailer')->send($message);
                }

                return $this->redirectToRoute('ft_personne_dashboard');
            }
        }

        return $this->render('FTProjetStageBundle:Etudiant:creerEquipe.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function deleteEtudiantEquipeAction(Equipe $equipe, Etudiant $etudiant)
    {
        //$personne = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $equipe = $em->getRepository('FTProjetStageBundle:Equipe')->find($equipe->getId());
        $etudiant = $em->getRepository('FTProjetStageBundle:Etudiant')->find($etudiant->getId());

        $etudiants = $equipe->getEtudiants();
        foreach ($etudiants as $etudiantDelete) {
            if ($etudiantDelete->getId() == $etudiant->getId()) {
                $equipe->removeEtudiant($etudiant);
                $em->flush();
                return $this->redirectToRoute('ft_personne_mesEquipes', array('id' => $equipe->getId()));
            }
        }

        throw new Exception('Erreur, l\'étudiant en question n\'est pas dans l\'équipe');
    }
}