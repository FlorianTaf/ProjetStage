<?php
/**
 * Created by PhpStorm.
 * User: Florian
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

class RegistrationController extends Controller
{
    public function vueConnexionAction()
    {
        return $this->render('FTProjetStageBundle:Registration:connexion.html.twig');
    }

    public function inscriptionAction(Request $request)
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
                } else {
                    $etudiant = new Etudiant();
                    $personne->setEtudiant($etudiant);
                }
                $em->persist($personne);
                $em->flush();

                return $this->render('FTProjetStageBundle::index.html.twig');
            }
        }
        return $this->render('FTProjetStageBundle:Registration:inscription.html.twig', array('form' => $form->createView()));
    }

    public function vueResetPasswordAction()
    {
        return $this->render('FTProjetStageBundle:Registration:resetPassword.html.twig');
    }

    public function memberInscriptionAction(Request $request, Personne $personne)
    {
        $entity = $this->getDoctrine()->getManager()->getRepository('FTProjetStageBundle:Personne');
        if ($entity != null) {
            $request->getSession()->getFlashBag()->add('warning', 'Un utilisateur portant ce nom et/ou cet email existe déjà');
            return $this->render('ft_user_inscription', array('personne' => $entity));
        }
    }
}