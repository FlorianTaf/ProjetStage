<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 22/09/2017
 * Time: 10:39
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Entity\Equipe;
use FT\ProjetStageBundle\Form\EquipeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

                $projets = $form->get('projets')->getData();
                foreach ($projets as $projet) {
                    var_dump('pdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpdpd');
                    var_dump($projet->getTitre());
                }
                var_dump(count($projets));
                //var_dump($projets);

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
}