<?php
/**
 * Created by PhpStorm.
 * User: Florian
 * Date: 01/10/2017
 * Time: 11:01
 */

namespace FT\ProjetStageBundle\Controller;


use FT\ProjetStageBundle\Datatables\PersonneDatatable;
use FT\ProjetStageBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $em->remove($personneDelete);
        $em->flush();

        return $this->redirectToRoute('ft_admin_personnes_liste');
    }
}