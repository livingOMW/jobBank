<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Administrador;
use AppBundle\Form\Type\AdministradorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditarAdminController extends Controller
{

  /**
   * @Route("/editar_admin", name="editar_admin")
   * @return \Symfony\Component\HttpFoundation\Response
   */
    public function editarAdminAction(Request $request)
    {
      //recuperamos el rol del usuario logeado si tiene ROLE_ADMIN seguimos con el proceso
      //si no lanzamos una excepcion
      $role=$this->getUser()->getRoles();

      if($role[0]!='ROLE_ADMIN')
      throw new NotFoundHttpException("Page not found");

      //recuperamos el id del usuaruio logeado
      $getid = $this->getUser()->getId();

      //recuperamos al adminsitrador
      $admin = new Administrador();
      $admin= $this->getDoctrine()
        ->getRepository('AppBundle:Administrador')
        ->find($getid);
      //creamos el formulario con el adminsitrador
      $form = $this->createForm(AdministradorType::class, $admin);

      $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid())
        {
          $password=$this
            ->get('security.password_encoder')
            ->encodePassword(
              $admin,
              $admin->getPlainPassword()
            );

          $admin->setPassword($password);

           $em = $this->getDoctrine()->getManager();
           $em->persist($admin);
           $em->flush();

           $this->addFlash('success', '¡Administrador se actualizó con exito!');
           return $this->redirectToRoute('homepage');
        }

    return $this->render('editar/administrador.html.twig',
    array('form' => $form->createView()));
  }
}
