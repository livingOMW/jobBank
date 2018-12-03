<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Alumno;
use AppBundle\Form\Type\AlumnoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditarAlumnoController extends Controller
{

/**
 * @Route("/editar_alumno", name="editar_alumno")
 * @return \Symfony\Component\HttpFoundation\Response
 */
  public function editarAlumnoAction(Request $request)
  {
    //recuperamos el rol del usuario logeado si tiene ROLE_USER seguimos con el proceso
    //si no lanzamos una excepcion
    $role=$this->getUser()->getRoles();
    if($role[0]!='ROLE_USER')
    throw new NotFoundHttpException("Page not found");

    //recuperamos el id del usuaruio logeado
    $getid = $this->getUser()->getId();
    //cargamos al usuario
    $alumno = new Alumno();
    $alumno= $this->getDoctrine()
      ->getRepository('AppBundle:Alumno')
      ->find($getid);
    //creamos el formulario con los datos del alumno
    $form = $this->createForm(AlumnoType::class, $alumno);

    $form->handleRequest($request);

      if ( $form->isSubmitted() && $form->isValid())
      {
        if(!$alumno->getPlainPassword())
        {
          $this->addFlash('danger', '¡Hay que introducir la contraseña!');
          return $this->render('editar/alumno.html.twig',
          array('form' => $form->createView()));
        }

        $password=$this
          ->get('security.password_encoder')
          ->encodePassword(
            $alumno,
            $alumno->getPlainPassword()
          );

        $alumno->setPassword($password);

         $em = $this->getDoctrine()->getManager();
         $em->persist($alumno);
         $em->flush();

         $this->addFlash('success', '¡El alumno se actualizó con exito!');
         return $this->redirectToRoute('homepage');
       }
    return $this->render('editar/alumno.html.twig',
    array('form' => $form->createView()));
  }
}
