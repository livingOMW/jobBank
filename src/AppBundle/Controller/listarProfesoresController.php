<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Profesor;
use AppBundle\Form\Type\ProfesorType;

class listarProfesoresController extends Controller
{

  /**
   * @Route("/listarProf", name="listarProf")
   *
   */
  public function listarProfesoresAction(Request $request)
  {
	  $em    = $this->get('doctrine.orm.entity_manager');
		$dql   = "SELECT a FROM AppBundle:Profesor a";
		$query = $em->createQuery($dql);

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query, /* query NOT result */
			$request->query->getInt('page', 1)/*page number*/,
			10/*limit per page*/
		);

     return $this->render('lista/listaProfesores.html.twig',
     array('pagination'=> $pagination)
    );

  }

  /**
 * @Route("/editarProfesor/{getid}", name="editar_Prof")
 * @return \Symfony\Component\HttpFoundation\Response
 */
 /*
  public function editarProfesorIdAction(Request $request,$getid)
  {

    $profesor = new Profesor();
    $profesor= $this->getDoctrine()
      ->getRepository('AppBundle:Profesor')
      ->find($getid);

    $form = $this->createForm(ProfesorType::class, $profesor);
    $form->handleRequest($request);

      if ( $form->isSubmitted() && $form->isValid())
      {
        $password=$this
          ->get('security.password_encoder')
          ->encodePassword(
            $profesor,
            $profesor->getPlainPassword()
          );

        $profesor->setPassword($password);

         $em = $this->getDoctrine()->getManager();
         $em->persist($profesor);
         $em->flush();

         $this->addFlash('success', '¡El profesor se actualizó con éxito!');
         return $this->redirectToRoute('listarProf');

       }

        return $this->render('editar/profesor.html.twig',
        array('form' => $form->createView()));
  }*/

  /**
    * @Route("/eliminar_profesor/{getid}", name="eliminar_profesor")
    */
   public function eliminaGrupo($getid)
   {
     $profesor = $this->getDoctrine()
             ->getRepository('AppBundle:Profesor')
             ->find($getid);

     $em=$this->getDoctrine()->getEntityManager();
     $em->remove($profesor);
     $em->flush();

     $this->addFlash('success', '¡El profesor se eliminó con éxito!');
     return $this->redirectToRoute('listarProf');
   }

}
