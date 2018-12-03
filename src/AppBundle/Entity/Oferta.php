<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Oferta
 *
 * @ORM\Table(name="oferta")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OfertaRepository")
 */
class Oferta
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
     */
    private $texto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToMany(targetEntity="Curso", inversedBy="ofertas")
     * @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
     */
    private $cursos;

    # Atributo necesario para insertar el input de los archivos
    # No tiene correspondencia con la base de datos
    private $archivos;

//    /**
//     * @ORM\OneToMany(targetEntity="Archivo", mappedBy="oferta")
//     */
//    private $archivos;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Oferta
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set texto
     *
     * @param string $texto
     *
     * @return Oferta
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Oferta
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set archivos
     *
     * @param \stdClass $archivos
     *
     * @return Oferta
     */
    public function setArchivos($archivos)
    {
        $this->archivos = $archivos;

        return $this;
    }

    /**
     * Get archivos
     *
     * @return \stdClass
     */
    public function getArchivos()
    {
        return $this->archivos;
    }

    /**
     * @return mixed
     */
    public function getCursos()
    {
        return $this->cursos;
    }

    /**
     * @param mixed $cursos
     */
    public function setCursos($cursos)
    {
        $this->cursos = $cursos;
    }


}

