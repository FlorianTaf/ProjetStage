<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 21/09/2017
 * Time: 11:20
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Entity\Personne;
use FT\ProjetStageBundle\Form\PersonneEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class PersonneController extends Controller
{
    public function profileAction(Request $request)
    {
        $personne = $this->getUser();

        //On récupère l'email et le pseudo de l'utilisateur avant la modif du formulaire
        $emailBefore = $personne->getEmail();
        $usernameBefore = $personne->getUsername();

        //Variable erreur qui nous servir à savoir s'il y a eu une erreur dans l'envoie du formulaire (pseudo ou email déjà utilisé)
        $error = false;

        $form = $this->createForm(PersonneEditType::class, $personne);
        if ($request->isMethod('POST')) {
            if ($form->handleRequest($request)->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $email = $em->getRepository('FTProjetStageBundle:Personne')->findOneBy(array('email' => $personne->getEmail()));
                $username = $em->getRepository('FTProjetStageBundle:Personne')->findOneBy(array('username' => $personne->getUsername()));

                //Si l'adresse email est déjà utilisé
                if ($email != null && $email->getEmail() != $emailBefore) {
                    $errorEmail = new FormError('L\'adresse email est déjà utilisée!');
                    $form->get('email')->addError($errorEmail);
                    $error = true;
                }
                //Si le pseudo est déjà utilisé
                if ($username != null && $username->getUsername() != $usernameBefore) {
                    $username = new FormError('Le pseudo est déjà utilisé!');
                    $form->get('email')->addError($username);
                    $error = true;
                }

                //Si pas bon, on renvoie avec les erreurs
                if ($error == true) {
                    return $this->render('FTProjetStageBundle:Personne:profile.html.twig',
                        array('form' => $form->createView(),
                            'personne' => $personne));
                }

                //Si ok, on flush (pas besoin de persist car les entités nous sont fournis directement par Doctrine ici
                $em->flush();
                return $this->render('FTProjetStageBundle:Personne:profile.html.twig',
                    array('form' => $form->createView(),
                        'personne' => $personne));
            }
        }
        return $this->render('FTProjetStageBundle:Personne:profile.html.twig',
            array('form' => $form->createView(),
                'personne' => $personne));
    }

    public function modifPasswordAction(Request $request)
    {
        $personne = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $oldPassword = htmlspecialchars($request->request->get('_oldPassword'));
            $newPassword = htmlspecialchars($request->request->get('_newPassword'));
            $newPasswordConfirm = htmlspecialchars($request->request->get('_newPasswordConfirm'));

            //Si les mots de passe ne sont pas bons, on renvoie l'utilisateur vers le formulaire de modification de mdp
            if (!($this->get('security.password_encoder')->isPasswordValid($personne, $oldPassword))
                || ($newPassword != $newPasswordConfirm)
                || (!($this->get('security.password_encoder')->isPasswordValid($personne, $oldPassword)) && ($newPasswordConfirm != $newPasswordConfirm))) {
                $error = "Le(s) mot de passe(s) ne correspondent pas";
                return $this->render('FTProjetStageBundle:Personne:modifPassword.html.twig', array(
                    'error' => $error
                ));
            }

            //On hash le mot de passe
            $encodedPassword = $this->get('security.password_encoder')->encodePassword($personne, $newPassword);

            //Si c'est ok, on flush l'entité en modifiant bien le mpd
            $personne->setPassword($encodedPassword);
            $em->flush();

            return $this->redirectToRoute('ft_personne_profile');
        }
        return $this->render('FTProjetStageBundle:Personne:modifPassword.html.twig', array(
            'personne' => $personne
        ));
    }

    public function dashboardAction()
    {
        return $this->render('FTProjetStageBundle:Personne:dashboard.html.twig', array(
        ));
    }
}