<?php

namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use AppBundle\Validator\Constraints as AcmeAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Alumno
 *
 * @ORM\Table(name="alumno")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AlumnoRepository")
 * @UniqueEntity(fields="email", message="Correo electrónico usado por otro usuario registrado.")
 * @UniqueEntity(fields="nre", message="Nre está ocupado.")
 * @UniqueEntity(fields="dni", message="DNI usado por otro usuario registrado.")
 * @UniqueEntity(fields="exp", message="Exp está ocupado.")
 */
class Alumno implements AdvancedUserInterface, \Serializable
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
    * @Assert\NotBlank(
    *     message = "Debe introducir un nombre.")
    * @Assert\Regex(
    *     pattern="/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/",
    *     message="El nombre debe tener solo letras."
    * )
    * @ORM\Column(name="Nombre", type="string", length=255)
    */
    private $nombre;

    /**
     * @Assert\NotBlank(
     *     message = "Debe elegir un curso.")
     * @ORM\ManyToOne(targetEntity="Curso", inversedBy="alumnos")
     * @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
     */
    private $curso;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message = "Debe introducir primer apellido.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/",
     *     message="El primer apellido debe tener solo letras."
     * )
     * @ORM\Column(name="Apellido1", type="string", length=255)
     */
    private $apellido1;

    /**
     * @var string
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/",
     *     message="El segundo apellido debe tener solo letras."
     * )
     * @ORM\Column(name="Apellido2", type="string", length=255, nullable=true)
     */
    private $apellido2;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message = "Debe introducir un correo electrónico.")
     * @Assert\Email(
     *     message = "El correo electrónico '{{ value }}' no es válido.",
     *     checkMX = true
     * )
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var int
     * @Assert\NotBlank(
     *     message = "Debe introducir un NRE.")
     * @Assert\Length(
     *      max = 7,
     *      maxMessage = "NRE tiene que ser como máximo {{ limit }} dígitos."
     * )
     * @ORM\Column(name="nre", type="integer", unique=true)
     */
    private $nre;

    /**
     * @var int
     *
     * @Assert\Length(
     *      max = 4,
     *      maxMessage = "Número de expediente tiene que ser como máximo {{ limit }} dígitos."
     * )
     *
     * @ORM\Column(name="exp", type="integer", nullable=true, unique=true)
     */
    private $exp;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message = "Debe introducir un DNI.")
     * @AcmeAssert\MyDni
     * @ORM\Column(name="dni", type="string", length=9, nullable=false, unique=true)
     */
    private $dni;

    /**
     * @var int
     * @Assert\Length(
     *      min = 9,
     *      max = 9,
     *      exactMessage = "El número de teléfono debe tener {{ limit }} dígitos.",
     * )
     * @ORM\Column(name="telefono", type="integer", nullable=true)
     */
    private $telefono;

    /**
     * @var int
     * @Assert\NotBlank(
     *     message = "Debe introducir su año de promoción.")
     * @Assert\Range(
     *      min = 1975,
     *      max = 2050,
     *      minMessage = "El año debe ser como mínimo {{ limit }}",
     *      maxMessage = "El año debe ser como máximo {{ limit }}"
     * )
     * @ORM\Column(name="anyo_promocion", type="integer", nullable=false)
     */
    private $anyoPromocion;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

    /**
     *
     * @ORM\Column(name="nonlock", type="string", length=255)
     */
    private $nonlock;

    /**
     *
     * @ORM\Column(name="recuperar", type="string", length=255, nullable=true)
     */
    private $recuperar;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/",
     *     htmlPattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/",
     *     message="Debe introducir 6 caractares, una mayuscula, una minuscula y un número.")
     */
    private $plainPassword;

    /**
     * La fecha de creación
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    protected $fecha_creacion;

    /**
     *
     * @ORM\Column(name="desactivar", type="string", length=255, nullable=true)
     */
    private $desactivar;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPlainPassword()
    {
      return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
      $this->plainPassword=$plainPassword;
    }

    public function getDesactivar()
    {
      return $this->desactivar;
    }

    public function setDesactivar($desactivar)
    {
      $this->desactivar=$desactivar;
    }

    public function getNonlock()
    {
      return $this->nonlock;
    }

    public function setNonlock($nonlock)
    {
      $this->nonlock=$nonlock;
    }
    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Alumno
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }


    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido1
     *
     * @param string $apellido1
     *
     * @return Alumno
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     *
     * @return Alumno
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Alumno
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Set nre
     *
     * @param integer $nre
     *
     * @return Alumno
     */
    public function setNre($nre)
    {
        $this->nre = $nre;

        return $this;
    }

    /**
     * Get nre
     *
     * @return int
     */
    public function getNre()
    {
        return $this->nre;
    }

    /**
     * Set exp
     *
     * @param integer $exp
     *
     * @return Alumno
     */
    public function setExp($exp)
    {
        $this->exp = $exp;

        return $this;
    }

    /**
     * Get exp
     *
     * @return int
     */
    public function getExp()
    {
        return $this->exp;
    }

    /**
     * Set telefono
     *
     * @param integer $telefono
     *
     * @return Alumno
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return int
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set anyoPromocion
     *
     * @param integer $anyoPromocion
     *
     * @return Alumno
     */
    public function setAnyoPromocion($anyoPromocion)
    {
        $this->anyoPromocion = $anyoPromocion;

        return $this;
    }

    /**
     * Get anyoPromocion
     *
     * @return int
     */
    public function getAnyoPromocion()
    {
        return $this->anyoPromocion;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Alumno
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return bool
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Alumno
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function serialize()
  {
      return serialize([
          $this->id,
          $this->email,
          $this->password,
          $this->activo,
      ]);
  }

  public function unserialize($serialized)
  {
      list (
          $this->id,
          $this->email,
          $this->password,
          $this->activo,
          ) = unserialize($serialized);
  }

  public function getRoles()
  {
    return [
               'ROLE_USER',
           ];
  }

  public function getSalt()
  {

  }
  public function eraseCredentials()
  {

  }

    /**
     * Set dni
     *
     * @param string $dni
     *
     * @return Alumno
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set curso
     *
     * @param \AppBundle\Entity\Curso $curso
     *
     * @return Alumno
     */
    public function setCurso(\AppBundle\Entity\Curso $curso = null)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return \AppBundle\Entity\Curso
     */
    public function getCurso()
    {
        return $this->curso;
    }

    public function isAccountNonExpired()
    {
        return true;
    }
    //si el campo nonlock en bd esta a 1 permite el accesso.
    public function isAccountNonLocked()
    {
      if($this->getNonlock()==1)
        return true;
      return false;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->activo;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Alumno
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fecha_creacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    /**
     * Set recuperar
     *
     * @param string $recuperar
     *
     * @return Alumno
     */
    public function setRecuperar($recuperar)
    {
        $this->recuperar = $recuperar;

        return $this;
    }

    /**
     * Get recuperar
     *
     * @return string
     */
    public function getRecuperar()
    {
        return $this->recuperar;
    }
}
