<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Archivo;
use AppBundle\Entity\Oferta;
use AppBundle\Form\Type\OfertaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DefaultController extends Controller
{

  /**
   * @Route("/inicio", name="homepage")
   */
  public function index2Action()
  {

      return $this->render('default/portada.html.twig', [
      ]);
  }

  /**
   * @Route("/enviar", name="enviar")
   */
  public function enviarAction( Request $request,\Swift_Mailer $mailer)
  {
      //crear el formulario envio del email
      $oferta = new Oferta();
      $form = $this->createForm(OfertaType::class, $oferta);

      $form->handleRequest($request);

      if ( $form->isSubmitted() && $form->isValid())
      {
          $data = $form->getData();
          $cursos = $data->getCursos();
          if (!$cursos[0]) {
            $this->addFlash('danger', '¡Debe seleccionar por lo menos un grupo!');
            //despues de enviar email la ruta que carga
            return $this->redirectToRoute('enviar');
            # code...
          }
        // dump($cursos[0]);die;
          # Necesario para evitar que explote.
          # Symfony tiene un problema cuando se suben varios archivos simultaneamente

          $archivos = $oferta->getArchivos()->getRuta();
          $oferta->setArchivos(null);

          # Guardamos la oferta
          $oferta->setFecha(new \DateTime());
          $em = $this->getDoctrine()->getManager();
          $em->persist($oferta);
          $em->flush();

          ##### Persistencia de los archivos: #####
          $id_oferta = $oferta->getId();
          $rutas = array();

          foreach ($archivos as $item){
              # nombre del archivo
              $fileName = $item->getClientOriginalName();

              # ruta donde se guarda el archivo:
              $path = 'Archivos/'.$id_oferta .'/'. $fileName;
              $rutas[] = $path;

              # Translado del archivo:
              $item->move('Archivos/'. $id_oferta .'/', $fileName);

              # Guardado en base de datos:
              $file = new Archivo();
              $file->setName($fileName);
              $file->setOferta($id_oferta);
              $file->setRuta($path);
              $em->persist($file);
              $em->flush();
          }


          $mensaje = $data->getTexto();
          $titulo = $data->getTitulo();

      foreach ($cursos as $curso) {
        //recuperar los alumnos del curso seleccionado que estan activos y no bloqueados
  			$alumnos = $this->getDoctrine()
                  ->getRepository('AppBundle:Alumno')
                  ->findBy(['curso' => $curso, 'activo' => '1', 'nonlock' => '1']);
        //enviar email a cada alumno
          foreach ($alumnos as $alumno) {
            $message = (new \Swift_Message('Oferta'))
                ->setFrom('infojoseplanes@gmail.com')
                ->setTo($alumno->getEmail())
                ->setSubject($titulo)
                ->setBody(
                      $this->renderView(
                          // app/Resources/views/Emails/registration.html.twig
                          '/email_send/email.html.twig',
                          array('mensaje' => $mensaje, 'codigo' => $alumno->getDesactivar())
                      ),
                      'text/html'
                  );
            foreach ($rutas as $ruta){
                $message->attach(\Swift_Attachment::fromPath($ruta));
            }
            $mailer->send($message);
          }
        }

       $this->addFlash('success', '¡La oferta se envió con éxito!');
       //despues de enviar email la ruta que carga
       return $this->redirectToRoute('enviar');
      }

    return $this->render('email_send/enviarOfertas.html.twig', [
        'form' => $form->createView(),
    ]);
   }
}
