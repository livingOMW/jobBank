<?php

namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Validator\Constraints as AcmeAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Profesor
 *
 * @ORM\Table(name="profesor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfesorRepository")
 * @UniqueEntity(fields="email", message="Correo electrónico está ocupado.")
 */
class Profesor implements UserInterface, \Serializable
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message = "Debe introducir primer apellido.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/",
     *     message="El primer apellido debe tener solo letras."
     * )
     * @ORM\Column(name="apellido1", type="string", length=255)
     */
    private $apellido1;

    /**
     * @var string
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/",
     *     message="El segundo apellido debe tener solo letras."
     * )
     * @ORM\Column(name="apellido2", type="string", length=255, nullable=true)
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
     * @var string
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
    /**
     * @var string
     *@Assert\NotBlank(message="Debe introducir una contraseña!")
     *
     */
    private $plainPassword;

    /**
     * Get email
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    public function getPlainPassword()
    {
      return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
      $this->plainPassword=$plainPassword;
    }
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Profesor
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
     * @return Profesor
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
     * @return Profesor
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
     * @return Profesor
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
     * Set password
     *
     * @param string $password
     *
     * @return Profesor
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
      ]);
  }

  public function unserialize($serialized)
  {
      list (
          $this->id,
          $this->email,
          $this->password
          ) = unserialize($serialized);
  }

  public function getRoles()
  {
    return [
               'ROLE_PROFESOR',
           ];
  }

  public function getSalt()
  {

  }
  public function eraseCredentials()
  {

  }
}
