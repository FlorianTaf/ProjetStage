<?php
/**
 * Created by PhpStorm.
 * Personne: Florian
 * Date: 15/09/2017
 * Time: 18:05
 */

namespace FT\ProjetStageBundle\Controller;

use Doctrine\ORM\EntityRepository;
use FT\ProjetStageBundle\Entity\Formateur;
use FT\ProjetStageBundle\Entity\Etudiant;
use FT\ProjetStageBundle\Entity\Personne;
use FT\ProjetStageBundle\Form\PersonneType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    public function registrationAction(Request $request)
    {
        $error = false;

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('nom', TextType::class, array(
                'label' => 'Nom', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                    'class' => 'form-control', 'placeholder' => 'Taffaneau')))
            ->add('prenom', TextType::class, array(
                'label' => 'Prénom', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                    'class' => 'form-control', 'placeholder' => 'Florian')))
            ->add('username', TextType::class, array(
                'label' => 'Pseudo', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                    'class' => 'form-control', 'placeholder' => 'Altoros')))
            ->add('telephone', TextType::class, array(
                'label' => 'Téléphone', 'required' => false, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                    'class' => 'form-control', 'placeholder' => '06/88/49/43/58')))
            ->add('email', EmailType::class, array(
                'label' => 'Email', 'required' => true, 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                    'class' => 'form-control', 'placeholder' => 'florian.taffaneau@laposte.net')))
            ->add('password', PasswordType::class, array(
                'label' => 'Mot de passe', 'required' => true, 'data' => 'etudiant', 'label_attr' => array(
                    'class' => 'form-control'), 'attr' => array(
                    'class' => 'form-control', 'id' => 'password', 'placeholder' => 'mdp123')))
            ->add('role', EntityType::class, array(
                'class' => 'FTProjetStageBundle:Role',
                //'data' => $this->em->getReference('FTProjetStageBundle:Role', 1),
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.name != :roleAdmin')->setParameter('roleAdmin', 'ROLE_ADMIN');
                },
                'choice_label' => 'name',
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label' => 'Role',
                'label_attr' => array(
                    'class' => 'form-control'
                ),
            ))
            ->add('save', SubmitType::class, array(
                'attr' => array(
                    'class' => 'login-button')))
            ->getForm();

        if ($request->isMethod('POST')) {
            if ($form->handleRequest($request)->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $data = $form->getData(); // On récupère toutes les données saisies dans le formulaire

                //On vérifie que l'adresse n'est pas utilisée
                $mail = $em->getRepository('FTProjetStageBundle:Personne')->findBy(array('email' => $data['email']));
                if ($mail != null) {
                    $errorMail = new FormError("Erreur, cette adrese email est déjà utilisée");
                    $form->get('email')->addError($errorMail);
                    $error = true;
                }
                //On vérifie que l'email n'est pas utilisé
                $pseudo = $em->getRepository('FTProjetStageBundle:Personne')->findBy(array('username' => $data['username']));
                if ($pseudo != null) {
                    $errorPseudo = new FormError("Erreur, ce pseudo est déjà utilisé");
                    $form->get('username')->addError($errorPseudo);
                    $error = true;
                }

                //On récupère le password et le password_confirm pour les comparer
                $password = htmlspecialchars($data['password']);
                $password_confirm = htmlspecialchars($request->request->get('password_confirm'));

                //On vérifie les 2 mots de passe
                if ($password != $password_confirm) {
                    $errorPassword = new FormError('Vos mots de passe ne correspondent pas!');
                    $form->get('password')->addError($errorPassword);
                    $error = true;
                }

                if ($error === true) {
                    return $this->render('FTProjetStageBundle:Registration:inscription.html.twig',
                        array('form' => $form->createView()));
                }

                //On check si la personne est étudiante ou formateur
                $role = $data['role'];
                if ($role->getName() === 'ROLE_FORMATEUR') {
                    //$personne->setRole($role);
                    $formateur = new Formateur();
                    $formateur->setNom($data['nom']);
                    $formateur->setPrenom($data['prenom']);
                    $formateur->setEmail($data['email']);
                    $formateur->setTelephone($data['telephone']);
                    $formateur->setUsername($data['username']);
                    $formateur->setRole($role);
                    //On va hasher le mot de passe
                    $encodedPassword = $this->get('security.password_encoder')->encodePassword($formateur, $password);
                    $formateur->setPassword($encodedPassword);
                    $em->persist($formateur);
                } else {
                    //$personne->setRole($role);
                    $etudiant = new Etudiant();
                    $etudiant->setNom($data['nom']);
                    $etudiant->setPrenom($data['prenom']);
                    $etudiant->setEmail($data['email']);
                    $etudiant->setTelephone($data['telephone']);
                    $etudiant->setUsername($data['username']);
                    $etudiant->setRole($role);
                    //On va hasher le mot de passe
                    $encodedPassword = $this->get('security.password_encoder')->encodePassword($etudiant, $password);
                    $etudiant->setPassword($encodedPassword);
                    $em->persist($etudiant);
                }

                $em->flush();

                return $this->render('FTProjetStageBundle::index.html.twig');
            }
        }
        return $this->render('FTProjetStageBundle:Registration:inscription.html.twig', array('form' => $form->createView()));
    }

    //Méthode à coder avec envoi de mail (swiftmailer)
    public function resetPasswordAction()
    {
        return $this->render('FTProjetStageBundle:Registration:resetPassword.html.twig');
    }
}