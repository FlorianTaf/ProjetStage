<?php
/**
 * Created by PhpStorm.
 * Personne: Florian
 * Date: 15/09/2017
 * Time: 18:05
 */

namespace FT\ProjetStageBundle\Controller;

use FT\ProjetStageBundle\Entity\Formateur;
use FT\ProjetStageBundle\Entity\Etudiant;
use FT\ProjetStageBundle\Entity\Personne;
use FT\ProjetStageBundle\Form\PersonneType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegistrationController extends Controller
{
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('ft_projet_stage_homepage');
        }

        // Le service authentication_utils permet de récupérer le nom d'utilisateur
        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
        // (mauvais mot de passe par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('FTProjetStageBundle:Registration:connexion.html.twig', array(
            'lastUsername' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    public function registrationAction(Request $request)
    {
        $error = false;

        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        if ($request->isMethod('POST')) {
            if ($form->handleRequest($request)->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $data = $form->getData(); // On récupère toutes les données saisies dans le formulaire

                //On vérifie que l'adresse n'est pas utilisée
                $mail = $em->getRepository('FTProjetStageBundle:Personne')->findBy(array('email' => $data->getEmail()));
                if ($mail != null) {
                    $errorMail = new FormError("Erreur, cette adrese email est déjà utilisée");
                    $form->get('email')->addError($errorMail);
                    $error = true;
                }

                //On récupère le password et le password_confirm pour les comparer
                $password = $data->getPassword();
                $password_confirm = $request->request->get('password_confirm');
                //On vérifie les 2 mots de passe
                if ($password != $password_confirm) {
                    $errorPassword = new FormError('Vos mots de passe ne correspondent pas!');
                    $form->get('password')->addError($errorPassword);
                    $error = true;
                }

                if ($error === true) {
                    return $this->render('FTProjetStageBundle:Registration:inscription.html.twig',
                        array('form' => $form->createView(),
                            'personne' => $personne));
                }

                //On check si la personne est étudiante ou formateur
                $type = $request->request->get('type');
                if ($type === 'formateur') {
                    $formateur = new Formateur();
                    $personne->setFormateur($formateur);
                    $personne->setRole(2);
                } else {
                    $etudiant = new Etudiant();
                    $personne->setEtudiant($etudiant);
                    $personne->setRole(1);
                }
                $em->persist($personne);
                $em->flush();

                return $this->render('FTProjetStageBundle::index.html.twig');
            }
        }
        return $this->render('FTProjetStageBundle:Registration:inscription.html.twig', array('form' => $form->createView()));
    }
}