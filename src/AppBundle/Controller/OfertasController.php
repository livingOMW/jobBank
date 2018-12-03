<?php
# Detalles sobre las ofertas

namespace AppBundle\Controller;


use AppBundle\Entity\Archivo;
use AppBundle\Entity\Oferta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OfertasController extends Controller
{
    /**
     * @Route("/ofertas", name="ofertas_lista")
     */
    public function ofertasAction()
        {
        $rol = $this->getUser()->getRoles();

        if($rol[0] == 'ROLE_USER'){
            $curso = $this->getUser()
                ->getCurso()
                ->getId();
            $ofertas = $this->getDoctrine()
                ->getRepository(Oferta::class)
                ->getOfertasByCurso($curso);

        }
        else{
            $ofertas = $this->getDoctrine()
                ->getRepository(Oferta::class)->findBy([], ['fecha' => 'DESC']);;
        }

        return $this->render('ofertas/listaOfertas.html.twig', [
            'ofertas' => $ofertas
        ]);
    }


    /**
     * @Route("/ofertas/{id}", name="ofertas")
     *
     */
    public function ofertaDetalleAction($id){

        $repository = $this->getDoctrine()->getRepository(Oferta::class);
        $oferta = $repository->find($id);
        $repository = $this->getDoctrine()->getRepository(Archivo::class);
        $archivos = $repository->findBy(['oferta' => $oferta->getId()]);
        return $this->render('ofertas/oferta.html.twig',[
            'oferta' => $oferta,
            'archivos' => $archivos,
        ]);
    }

    /**
     * @Route("/eliminaroferta/{getid}", name="eliminar_oferta")
     */
    public function eliminaOferta($getid)
    {
      $oferta = $this->getDoctrine()
              ->getRepository('AppBundle:Oferta')
              ->find($getid);

      $em=$this->getDoctrine()->getEntityManager();
      $em->remove($oferta);
      $em->flush();

      $this->addFlash('success', '¡Su oferta se eliminó con éxito!');
      return $this->redirectToRoute('ofertas_lista');
    }

}
