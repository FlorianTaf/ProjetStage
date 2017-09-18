<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 15/09/2017
 * Time: 18:05
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegistrationController extends Controller
{
    public function vueConnexionAction()
    {
        return $this->render('FTProjetStageBundle:Registration:connexion.html.twig');
    }

    public function vueInscriptionAction()
    {
        return $this->render('FTProjetStageBundle:Registration:inscription.html.twig');
    }

    public function vueResetPasswordAction()
    {
        return $this->render('FTProjetStageBundle:Registration:resetPassword.html.twig');
    }

    public function memberInscriptionAction(Request $request, Personne $personne)
    {
        $entity = $this->getDoctrine()->getManager()->getRepository('FTProjetStageBundle:Personne');
        if ($entity !== null){
            $request->getSession()->getFlashBag()->add('warning', 'Un utilisateur portant ce nom et/ou cet email existe déjà');
            return $this->render('ft_user_inscription', array('personne' => $entity));
        }

    }
}