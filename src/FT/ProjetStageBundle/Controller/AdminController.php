<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 01/10/2017
 * Time: 11:01
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Datatables\PersonneDatatable;
use FT\ProjetStageBundle\Entity\Etudiant;
use FT\ProjetStageBundle\Entity\Formateur;
use FT\ProjetStageBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('FTProjetStageBundle:Admin:dashboard.html.twig');
    }

    public function listePersonnesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $personnes = $em->getRepository('FTProjetStageBundle:Personne')->findAll();

        //var_dump($personnes);

        return $this->render('FTProjetStageBundle:Admin:listePersonnes.html.twig', array(
            'personnes' => $personnes
        ));

        //$isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.post');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        //** @var DatatableInterface $datatable */

        /*
        $datatable = $this->get('sg_datatables.factory')->create(PersonneDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('FTProjetStageBundle:Admin:listePersonnes.html.twig', array(
            'datatable' => $datatable,
        ));
        */
    }

    public function deletePersonneAction(Personne $personne)
    {
        $em = $this->getDoctrine()->getManager();

        $personneDelete = $em->getRepository('FTProjetStageBundle:Personne')->find($personne->getId());

        if ($personneDelete === null) {
            throw new NotFoundHttpException("Erreur, la personne demandée n'exite pas !!!");
        }

        //On vérifie si la personne n'est pas propriétaire d'un projet ou d'une équipe
        if ($personneDelete instanceof Etudiant) {
            $equipes = $em->getRepository('FTProjetStageBundle:Equipe')->findBy(array(
                'proprietaire' => $personneDelete
            ));
            //Si c'est pas null, alors la personne est propriétaire d'une équipe
            if ($equipes != null ) {
                throw new Exception("Impossible de supprimer cet étudiant, il est propriétaire d'une équipe !!!");
            }
        }
        if ($personneDelete instanceof Formateur) {
            $projets = $em->getRepository('FTProjetStageBundle:Projet')->findBy(array(
                'proprietaire' => $personneDelete
            ));
            //Si c'est pas null, alors la personne est propriétaire d'un projet
            if ($projets != null) {
                throw new Exception("Impossibler de supprimer ce formateur, il est propriétaire d'un projet !!!");
            }
        }

        $em->remove($personneDelete);
        $em->flush();

        return $this->redirectToRoute('ft_admin_personnes_liste');
    }
