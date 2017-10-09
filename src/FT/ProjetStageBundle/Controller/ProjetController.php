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

        return $this->render('FTProjetStageBundle:Projet:liste.html.twig', array(
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

    public function creerProjetAction(Request $request)
    {
        $personne = $this->getUser();
        $projet = new Projet();

        $form = $this->createForm(ProjetType::class, $projet);

        if ($request->isMethod('POST')) {
            if ($form->handleRequest($request)->isValid()) {
                $em = $this->getDoctrine()->getManager();

                //On stocke le datetime et la date limite dans des variables
                $dateTime = new \DateTime();
                $dateLimite = $projet->getDateLimite();
                //On définit l'interval de jours qu'il y a
                $interval = $dateTime->diff($dateLimite);
                $days = $interval->format('%a');

                //On vérifie que la date limite soit supérieure d'au moins 7 jours à la date de création
                if ($dateTime > $dateLimite || $days < 7) {
                    $errorDate = new FormError('Erreur, la date limite doit être supérieure ou égale à 7 jours de la date de création');
                    $form->get('dateLimite')->addError($errorDate);
                    return $this->render('FTProjetStageBundle:Formateur:creerProjet.html.twig', array(
                        'form' => $form->createView(),
                        'personne' => $personne
                    ));
                }

                //Si c'est ok, on valide les champs qui ne sont pas demandés à l'utilisateur
                $projet->setDateCreation(new \DateTime());
                $projet->setProprietaire($personne);

                //On persiste et on flush
                $em->persist($projet);
                $em->flush();

                return $this->redirectToRoute('ft_personne_dashboard');
            }
        }

        return $this->render('FTProjetStageBundle:Formateur:creerProjet.html.twig', array(
            'form' => $form->createView(),
            'personne' => $personne
        ));
    }
}