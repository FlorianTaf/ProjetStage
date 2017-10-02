<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 02/10/2017
 * Time: 17:36
 */

namespace FT\ProjetStageBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class MessageController extends Controller
{
    public function listeMessagesActon()
    {
        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository('FTProjetStageBundle:Message')->findAll();

        $tableau = array();

        foreach ($messages as $message) {
            $tableau[] = $message;
        }

        return new JsonResponse(array(
            'tableau' => $tableau
        ));
    }
}