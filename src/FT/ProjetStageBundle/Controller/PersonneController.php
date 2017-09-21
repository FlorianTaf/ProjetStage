<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 21/09/2017
 * Time: 11:20
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Form\PersonneType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonneController extends Controller
{
    public function profileAction(Request $request)
    {
        $personne = $this->getUser();
        $form = $this->createForm(PersonneType::class, $personne);

        return $this->render('FTProjetStageBundle:Personne:profile.html.twig', array(
            'form' => $form->createView(),
            'personne' => $personne
        ));
    }
}