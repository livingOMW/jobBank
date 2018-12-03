<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Validator\Constraints as AcmeAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Administrador
 *
 * @ORM\Table(name="administrador")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdministradorRepository")
 * @UniqueEntity(fields="email", message="El correo electrónico está ocupado.")
 */
class Administrador implements UserInterface, \Serializable
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
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var string
     * @Assert\NotBlank(
     *     message = "Debe introducir un correo electrónico.")
     * @Assert\Email(
     *     message = "El correo electronico '{{ value }}' no es válido.",
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
     * @return Administrador
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
     * Set password
     *
     * @param string $password
     *
     * @return Administrador
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
          $this->nombre,
          $this->password,
      ]);
  }

  /**
   * Get nombre
   *
   * @return string
   */
  public function getUsername()
  {
      return $this->nombre;
  }

  public function unserialize($serialized)
  {
      list (
          $this->id,
          $this->nombre,
          $this->password
          ) = unserialize($serialized);
  }

  public function getRoles()
  {
    return [
               'ROLE_ADMIN',
           ];
  }

  public function getSalt()
  {

  }
  public function eraseCredentials()
  {

  }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Administrador
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
}
