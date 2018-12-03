<?php
// src/AppBundle/Validator/Constraints/ContainsAlphanumeric.php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MyDni extends Constraint
{
  public $message = 'DNI o NIE no válido.';
}
