<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Administrador;
use AppBundle\Form\Type\AdministradorType;

class listarAdministradoresController extends Controller
{

  /**
   * @Route("/listarAdmin", name="listarAdmin")
   *
   */
  public function listarAdministradoresAction(Request $request)
  {
	  $em    = $this->get('doctrine.orm.entity_manager');
		$dql   = "SELECT a FROM AppBundle:Administrador a";
		$query = $em->createQuery($dql);

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query, /* query NOT result */
			$request->query->getInt('page', 1)/*page number*/,
			10/*limit per page*/
		);

     return $this->render('lista/listaAdmin.html.twig',
     array('pagination'=> $pagination)
    );

  }

}
