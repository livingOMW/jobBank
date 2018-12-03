<?php
/**
 * Created by PhpStorm.
 * User: pagan
 * Date: 2018-06-01
 * Time: 00:53
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Archivo;
use AppBundle\Entity\Oferta;

class ArchivoRepository extends \Doctrine\ORM\EntityRepository
{
    public function appendArchivos($notificaciones){
        $ofertas = array();
        $archivos = array();
        foreach ($notificaciones as $nota){

            $repository = $this->getEntityManager()
                ->getRepository(Archivo::class)
                ->findBy(
                    ['oferta' => $nota["id"]]
                );

            if($repository != null){

                $rutas = array();

                foreach ($repository as $ruta){
                    $rutas[] = $ruta->getRuta();
                }
                $archivos[] = $rutas;

            }else {
                $archivos[] = null;
            }

        }

        return $archivos;
    }


}

