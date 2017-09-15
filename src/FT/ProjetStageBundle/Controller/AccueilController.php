<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 15/09/2017
 * Time: 14:45
 */

namespace FT\ProjetStageBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    public function indexAction()
    {
        return $this->render('FTProjetStageBundle::index.html.twig');
    }
}