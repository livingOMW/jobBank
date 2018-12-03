<?php
// src/AppBundle/Validator/Constraints/ContainsAlphanumeric.php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsAlphanumeric extends Constraint
{
    public $message = 'Este "{{ string }}" ya esta ocupado.';
}
