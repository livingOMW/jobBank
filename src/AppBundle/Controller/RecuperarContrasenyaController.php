<?php
namespace AppBundle\Controller;
use Symfony\Component\Form\FormBuilderInterface;

use AppBundle\Entity\Alumno;
use AppBundle\Form\Type\AlumnoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RecuperarContrasenyaController extends Controller
{

/**
 * @Route("/recuperarContrasenyaEmail", name="recuperarContrasenyaEmail")
 * @return \Symfony\Component\HttpFoundation\Response
 */
  public function recuperarContrasenyaEmailAction(Request $request,\Swift_Mailer $mailer)
  {

  //formulario para introducir el correo eléctrónico
  $form= $this->createFormBuilder()
        ->add('email', EmailType::class, [
                'label' => 'Introduce tu dirección de correo electrónico:',
                'attr' => [
                    'class' => 'col-sm-5'],
                'label_attr' => [
                    'class' => 'col-sm-5']
              ])
        ->add('Buscar', SubmitType::class,[
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ])
        ->getForm()
          ;

  $form->handleRequest($request);

  if ( $form->isSubmitted() && $form->isValid())
  {
      $data = $form->getData();

      $email = $data['email'];
      $alumno = new Alumno();
      $alumno= $this->getDoctrine()
        ->getRepository('AppBundle:Alumno')
        ->findOneByEmail($email);

    //Si el correo eléctrónico no esta registrado se lanza flash mensaje
      if(!$alumno)
      {
        $this->addFlash('danger', 'No existe ningún usuario en el sistema con esta dirección de correo electrónico.');
        return $this->redirectToRoute('recuperarContrasenyaEmail');
      }

      //generamos el código para guardarlo en la base de datos.
      $codigo=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.~"), 0, 40);
    //  $codigo=base64_encode(random_int(9999,999999999));
      $alumno->setRecuperar($codigo);

       $em = $this->getDoctrine()->getManager();
       $em->persist($alumno);
       $em->flush();

      //enviar Email con el código de recuperación de la constraseña
      $message = (new \Swift_Message('Contraseña'))
          ->setFrom('infojoseplanes@gmail.com')
          ->setTo($alumno->getEmail())
          ->setBody(
                $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                    '/registration/emails/emailRecuperar.html.twig',
                    array('codigo' => $codigo)
                ),
                'text/html'
            );
      $mailer->send($message);
      $this->addFlash('success', 'Le hemos enviado el correo electrónico con las instrucciones de recuperación de su contraseña.');
       return $this->redirectToRoute('homepage');

  //fin if
    }
      return $this->render('recuperar/form.html.twig', [
          'form' => $form->createView(),
      ]);

}

/**
 * @Route("/recuperarContrasenya/{codigo}", name="recuperarContrasenya",requirements={"token"=".+"})
 * @return \Symfony\Component\HttpFoundation\Response
 */
  public function recuperarAlumnoAction($codigo,Request $request)
  {
    //Buscamos al alumno con este código
    $alumno = new Alumno();
    $alumno= $this->getDoctrine()
      ->getRepository('AppBundle:Alumno')
      ->findOneByRecuperar($codigo);

    //Si el código introducido es incorrecto que el alumno no existe se lanza una excepción
    if(!$alumno)
    throw new NotFoundHttpException("Page not found");

  //  if($user->getId()!=$getid)
  //  throw new AccessDeniedException('Only an admin can do this!!!!');

    $form = $this->createForm(AlumnoType::class, $alumno);

    $form->handleRequest($request);

      if ( $form->isSubmitted() && $form->isValid())
      {
        $password=$this
          ->get('security.password_encoder')
          ->encodePassword(
            $alumno,
            $alumno->getPlainPassword()
          );

        $alumno->setPassword($password);
        $alumno->setRecuperar(null);

         $em = $this->getDoctrine()->getManager();
         $em->persist($alumno);
         $em->flush();

         $this->addFlash('success', '¡Su contraseña se actualizó con exito!');
         return $this->redirectToRoute('homepage');
      }
      return $this->render('recuperar/recuperarContrasenya.html.twig',
      array('form' => $form->createView()));
  }
}
