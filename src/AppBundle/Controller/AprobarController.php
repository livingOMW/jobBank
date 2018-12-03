<?php
//aprobar a los alumnos, acceso profesores y admin

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AprobarController extends Controller
{
  /**
   * @Route("/aprobar", name="aprobar")
   *
   */
  public function aprobarAlumnosAction(Request $request,\Swift_Mailer $mailer)
  {
    //1.Buscamos a todos los alumnos no activos y que han confirmado su cuenta.
      $alumnos= $this->getDoctrine()
        ->getRepository('AppBundle:Alumno')
        ->findBy(array('nonlock' => 1, 'activo' => 0));
    //2.Creamos la forma con alumnos y checkbox para cada uno con el id de alumno.
     $defaultData = array('message' => 'Alumnos Aprobados');
     $builder = $this->createFormBuilder($defaultData);

     foreach ($alumnos as $alumno) {
       $builder->add($alumno->getId(), CheckboxType::class, array(
       'label'    => $alumno->getNombre()
                    .' '.$alumno->getApellido1()
                    .' '.$alumno->getApellido2()
                    .' Email: '.$alumno->getEmail()
                    .' Grupo: '.$alumno->getCurso()->getName()
                    .' Año promoción: '.$alumno->getAnyoPromocion(),
       'required' => false,
       ));
     }

     $builder->add('Activar', SubmitType::class,[
         'attr' => [
             'class' => 'btn btn-success'
         ]
     ]);
     //3.Construimos el formulario
     $form= $builder->getForm();

     $form->handleRequest($request);
     $data=$form->getData();


     if ($form->isSubmitted() && $form->isValid())
     {

       $data = $form->getData();

       foreach( $data as $key => $value )
       {
        //si se ha marcado la casilla buscamos al alumno en BBDD y activamos al alumno.
         if(is_numeric($key) && $value==true)
         {
           $alumno = $this->getDoctrine()
          ->getRepository('AppBundle:Alumno')
          ->find($key);
          //activación del alumno
            $alumno->setActivo(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($alumno);
            $em->flush();

            //enviar Email al alumno que su cuenta se ha activado
            $message = (new \Swift_Message('Confirmación bolsa de trabajo IES José Planes'))
                ->setFrom('infojoseplanes@gmail.com')
                ->setTo($alumno->getEmail())
                ->setBody(
                    $this->renderView(
                        // app/Resources/views/Emails/registration.html.twig
                        '/registration/emails/suCuentaActiva.html.twig',
                        array('alumno' => $alumno)
                    ),
                    'text/html'
                );
            $mailer->send($message);
          }
        }
     //mensaje flash que sale al activar los alumnos
       $this->addFlash('success', '¡Los alumnos se activaron con éxito!');
       return $this->redirectToRoute('aprobar');
       }
     return $this->render('activarAlumno/activa.html.twig',
     array('form' => $form->createView(),'alumnos'=> $alumnos)
    );

  }

}
