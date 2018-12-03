<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Curso;
use AppBundle\Form\Type\CursoType;
use Symfony\Component\HttpFoundation\Response;

  class GrupoController extends Controller
  {

  /**
   * @Route("/grupos/listar_grupos", name="listar_grupos")
   */
  public function listarGrupos()
  {
    $grupos = $this->getDoctrine()
            ->getRepository('AppBundle:Curso')
            ->createQueryBuilder('c')
                ->orderBy('c.name', 'ASC')
                ->getQuery()
                ->getResult();
    //dump($grupos);die;
    return $this->render('grupos/listargrupos.html.twig', array('grupos'=> $grupos));
  }

  /**
   * @Route("/grupos/crear_grupos/", name="crear_grupo")
   */
   public function crearGrupos( Request $request)
   {

     $grupo = new Curso();
     $form = $this->createForm(CursoType::class, $grupo );

     $form->handleRequest( $request );

     if ($form->isValid())
     {
       $em = $this->getDoctrine()->getManager();
       $em->persist($grupo);
       $em->flush();
       $this->addFlash('success', '¡Grupo se ha creado con éxito!');

     return $this->redirectToRoute('listar_grupos');
     }
     else

     return $this->render('grupos/crearGrupo.html.twig', array('form' => $form->createView(),));
   }


   /**
    * @Route("/grupos/editar_grupo/{getid}", name="editar_grupo")
    * @return \Symfony\Component\HttpFoundation\Response
    */
   public function editarGrupo($getid,Request $request)
   {
     $grupo = new Curso();

     $grupo= $this->getDoctrine()
       ->getRepository('AppBundle:Curso')
       ->find($getid);

     $form = $this->createForm(CursoType::class, $grupo);

     $form->handleRequest($request);

     if ( $form->isSubmitted() && $form->isValid())
     {
     //  $grupo = $form->getData();

       // $grupo->setEspecialidad($grupo->getEspecialidad()->getId());
        $em = $this->getDoctrine()->getManager();
        $em->persist($grupo);
        $em->flush();

        $this->addFlash('success', '¡Su grupo se actualizó con éxito!');
        return $this->redirectToRoute('listar_grupos');

     }

     return $this->render('grupos/editarGrupo.html.twig', array('form' => $form->createView()));
   }

   /**
    * @Route("/grupos/eliminar_grupo/{getid}", name="eliminar_grupo")
    */
   public function eliminaGrupo($getid)
   {
     try{
     $grupo = $this->getDoctrine()
             ->getRepository('AppBundle:Curso')
             ->find($getid);

     $em=$this->getDoctrine()->getEntityManager();
     $em->remove($grupo);
     $em->flush();
   }catch(\Exception $e)
   {
     $this->addFlash('danger', '¡No se puede eliminar el grupo, tiene alumnos!');
     return $this->redirectToRoute('listar_grupos');
   }
     $this->addFlash('success', '¡Su grupo se eliminó con éxito!');
     return $this->redirectToRoute('listar_grupos');
   }

  }
