<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Alumno;
use AppBundle\Form\Type\AlumnoType;

class listarAlumnosController extends Controller
{

  /**
   * @Route("/listar", name="listar")
   *
   */
  public function listarAlumnosAction(Request $request)
  {
	  $em    = $this->get('doctrine.orm.entity_manager');
		$dql   = "SELECT a FROM AppBundle:Alumno a WHERE a.activo=1 and a.nonlock=1";
		$query = $em->createQuery($dql);

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query, /* query NOT result */
      //El número 10 indica cuantos alumnos va mostrar por página se puede modificar.
			$request->query->getInt('page', 1)/*page number*/,
			10/*limit per page*/
		);

     return $this->render('lista/listaAlumnos.html.twig',
     array('pagination'=> $pagination)
    );

  }

  /**
 * @Route("/editarAlumno/{getid}", name="editar_alumnoProf")
 * @return \Symfony\Component\HttpFoundation\Response
 */
  public function editarAlumnoIdAction(Request $request,$getid)
  {

    $alumno = new Alumno();
    $alumno= $this->getDoctrine()
      ->getRepository('AppBundle:Alumno')
      ->find($getid);

    $form = $this->createForm(AlumnoType::class, $alumno);
    $form->handleRequest($request);

      if ( $form->isSubmitted() && $form->isValid())
      {
        $datos=$form->getData();
        $passNew=$datos->getPlainPassword();
        $passOld=$datos->getPassword();
        if($passNew)
        {
        //dump($form->getData()->getPlanPassword());die;
        $password=$this
          ->get('security.password_encoder')
          ->encodePassword(
            $alumno,
            $alumno->getPlainPassword()
          );
        }
        else
        {
          $password=$passOld;
        }

        $alumno->setPassword($password);

         $em = $this->getDoctrine()->getManager();
         $em->persist($alumno);
         $em->flush();

         $this->addFlash('success', '¡Su alumno se actualizó con éxito!');
         return $this->redirectToRoute('listar');

       }
      // dump($form);die;
        return $this->render('editar/alumnoAdmin.html.twig',
        array('form' => $form->createView()));
  }

}
