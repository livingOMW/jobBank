<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Profesor;
use AppBundle\Form\Type\ProfesorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditarProfesorController extends Controller
{

/**
 * @Route("/editar_profesor", name="editar_profesor")
 * @return \Symfony\Component\HttpFoundation\Response
 */
  public function editarprofesorAction(Request $request)
  {
    //recuperamos el rol del usuario logeado si tiene ROLE_PROFESOR seguimos con el proceso
    //si no lanzamos una excepcion
    $role=$this->getUser()->getRoles();

    if($role[0]!='ROLE_PROFESOR')
    throw new NotFoundHttpException("Page not found");

    //recuperamos el id del usuaruio logeado
    $getid = $this->getUser()->getId();

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

       $this->addFlash('success', '¡El profesor se actualizó con exito!');
       return $this->redirectToRoute('homepage');
     }

    return $this->render('editar/profesor.html.twig',
    array('form' => $form->createView()));
  }
}
