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