<?php
/**
 * Created by PhpStorm.
 * User: pagan
 * Date: 2018-05-30
 * Time: 00:25
 */

namespace AppBundle\Repository;



use AppBundle\Entity\Archivo;
use AppBundle\Entity\Oferta;
use Doctrine\ORM\EntityManager;
use PDO;

/**
 * @property  EntityManager
 */
class OfertaRepository extends \Doctrine\ORM\EntityRepository
{
    private $EntityManager;

    public function getOfertasByCurso($curso){

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT o.* FROM oferta o JOIN oferta_curso c ON o.id = c.oferta_id WHERE c.curso_id = :curso ORDER BY o.fecha DESC ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['curso' => $curso]);

        $ofertas = $stmt->fetchAll();

        return $ofertas;
    }

    public function getOfertas(){

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT o.* FROM oferta o JOIN oferta_curso c ON o.id = c.oferta_id ORDER BY o.fecha DESC ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $ofertas = $stmt->fetchAll();

        return $ofertas;
    }
}