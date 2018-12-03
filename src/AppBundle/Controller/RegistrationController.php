<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Alumno;
use AppBundle\Form\Type\AlumnoType;
use AppBundle\Entity\Administrador;
use AppBundle\Form\Type\AdministradorType;
use AppBundle\Entity\Profesor;
use AppBundle\Form\Type\ProfesorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class RegistrationController extends Controller
{

/**
 * @Route("/register", name="register")
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function registerAction( Request $request,\Swift_Mailer $mailer)
{

  $member = new Alumno();
  $form = $this->createForm(AlumnoType::class, $member);

  $form->handleRequest($request);

  if ( $form->isSubmitted() && $form->isValid())
  {
    if(!$member->getPlainPassword())
    {
      $this->addFlash('danger', '¡Hay que introducir la contraseña!');
      return $this->render('registration/register.html.twig',
      array('form' => $form->createView()));
    }
    $password=$this
      ->get('security.password_encoder')
      ->encodePassword(
        $member,
        $member->getPlainPassword()
      );

    $member->setPassword($password);

    //la tiene que activar profesor o administrador
    $member->setActivo(false);
    //fecha de creacion de la cuenta
    $member->setFechaCreacion(new \DateTime());

    //codigo de confirmacion del email lo tiene que confirmar el usuario
    $codigo=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.~"), 0, 40);
    //codigo para desactivar la cuenta
    $desactivar=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.~"), 0, 40);

    $member->setNonlock($codigo);
    $member->setDesactivar($desactivar);
     $em = $this->getDoctrine()->getManager();
     $em->persist($member);
     $em->flush();

     //enviar Email con código de confirmación al alumno
     $message = (new \Swift_Message('Confirmación bolsa de trabajo IES José Planes'))
         ->setFrom('infojoseplanes@gmail.com')
         ->setTo($member->getEmail())
         ->setBody(
             $this->renderView(
                 // app/Resources/views/Emails/registration.html.twig
                 '/registration/emails/registration.html.twig',
                 array('codigo' => $codigo)
             ),
             'text/html'
         );
     $mailer->send($message);

     $this->addFlash('success', '¡Te has registrado con éxito, ahora tienes que confirmar tu email!');
     return $this->redirectToRoute('homepage');
  }

    return $this->render('registration/register.html.twig',
    array('form' => $form->createView()));
}

/**
 * @Route("/registerProfesor", name="registerProfesor")
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function registerProfesorAction( Request $request)
{

    $member = new Profesor();

    $form = $this->createForm(ProfesorType::class, $member);

    $form->handleRequest($request);

    if ( $form->isSubmitted() && $form->isValid())
    {
      $password=$this
        ->get('security.password_encoder')
        ->encodePassword(
          $member,
          $member->getPlainPassword()
        );

      $member->setPassword($password);

       $em = $this->getDoctrine()->getManager();
       $em->persist($member);
       $em->flush();

       $this->addFlash('success', '¡El profesor se registró con éxito!');

       return $this->redirectToRoute('homepage');
    }

    return $this->render('registration/registerProfesor.html.twig',
    array('form' => $form->createView()));
}

/**
 * @Route("/confirmar/{codigo}", name="confirmar",requirements={"token"=".+"})
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function confirmarAction( $codigo, Request $request, \Swift_Mailer $mailer)
{

  $alumno = $this->getDoctrine()
          ->getRepository('AppBundle:Alumno')
          ->findOneByNonlock($codigo);



  //dump($alumno);
  //si el alumno con este codigo existe lo activamos
  if($alumno && $codigo!=1)
  {
    $admins = $this->getDoctrine()
            ->getRepository('AppBundle:Administrador')
            ->findAll($codigo);

    $profesores = $this->getDoctrine()
            ->getRepository('AppBundle:Profesor')
            ->findAll($codigo);

     $alumno->setNonlock(true);
     $em = $this->getDoctrine()->getManager();
     $em->persist($alumno);
     $em->flush();

     //enviar Email a la profesora que hay alumnos por activar
    foreach ($admins as $admin) {

      $message = (new \Swift_Message('Confirmación bolsa de trabajo IES José Planes'))
          ->setFrom('infojoseplanes@gmail.com')
          ->setTo($admin->getEmail())
          ->setBody(
              $this->renderView(
                  // app/Resources/views/Emails/registration.html.twig
                  '/registration/emails/alumnosPorAprobar.html.twig',
                  array('alumno' => $alumno)
              ),
              'text/html'
          );
      $mailer->send($message);
    }

     foreach ($profesores as $profesor) {

       $message = (new \Swift_Message('Confirmación bolsa de trabajo IES José Planes'))
           ->setFrom('infojoseplanes@gmail.com')
           ->setTo($profesor->getEmail())
           ->setBody(
               $this->renderView(
                   // app/Resources/views/Emails/registration.html.twig
                   '/registration/emails/alumnosPorAprobarProfesor.html.twig',
                   array('alumno' => $alumno)
               ),
               'text/html'
           );
       $mailer->send($message);
     }

   return $this->render('registration/email_confirmation.html.twig',
   array('alumno'=> $alumno));
  }
  else
  {
   return $this->render('registration/error_confirmation.html.twig');
  }
}

/**
 * @Route("/registerAdmin", name="registerAdmin")
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function registerAdminAction( Request $request)
{

    $member = new Administrador();
    $form = $this->createForm(AdministradorType::class, $member);

    $form->handleRequest($request);

    if ( $form->isSubmitted() && $form->isValid())
    {
      $password=$this
        ->get('security.password_encoder')
        ->encodePassword(
          $member,
          $member->getPlainPassword()
        );

      $member->setPassword($password);

      $em = $this->getDoctrine()->getManager();

       $em->persist($member);
       $em->flush();

       $this->addFlash('success', '¡El administrador se ha registrado con éxito!');

       return $this->redirectToRoute('homepage');
    }

    return $this->render('registration/registerAdministrador.html.twig',
    array('form' => $form->createView()));
}
  }
