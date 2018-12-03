<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Alumno;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class desactivarAlumnoController extends Controller
{
  /**
   * @Route("/desactivar/{codigo}", name="desactivar",requirements={"token"=".+"})
   * @return \Symfony\Component\HttpFoundation\Response
   */
    public function recuperarAlumnoAction($codigo,Request $request)
    {
      //buscamos al alumno con este código
      $alumno = new Alumno();
      $alumno= $this->getDoctrine()
        ->getRepository('AppBundle:Alumno')
        ->findOneByDesactivar($codigo);

      //si alumno con este código no existe lanzamos una excepción
      if(!$alumno)
      throw new NotFoundHttpException("Page not found");
      $desactivar=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.~"), 0, 40);
      $alumno->setDesactivar($desactivar);
      $codigo=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.~"), 0, 40);
      $alumno->setNonlock($codigo);

       $em = $this->getDoctrine()->getManager();
       $em->persist($alumno);
       $em->flush();

       $this->addFlash('success', 'Su cuenta se desactivó, dejará de recibir correos.');

       return $this->render('desactivar/desactivarAlumno.html.twig',
       array('alumno' => $alumno));
     }
}
