<?php
// src/AppBundle/Validator/Constraints/ContainsAlphanumericValidator.php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MyDniValidator extends ConstraintValidator
{
  /**
   * {@inheritdoc}
   */
    public function validate($dni, Constraint $constraint)
    {
        if (!$constraint instanceof MyDni) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\MyDni');
        }

        	$letra = substr($dni, -1);
          $letra=mb_strtoupper($letra);
        	$numeros = substr($dni, 0, -1);
          $verdad=false;
          $primera=mb_strtoupper(substr($dni,0,1));

          if(!is_numeric($primera))
          {
            if($primera=="X")
            $numeros= 0 . substr($numeros,1);
            else if($primera=="Y")
            $numeros= 1 . substr($numeros,1);
            else if($primera=="Z")
            $numeros= 2 . substr($numeros,1);
          }


          if(is_numeric($numeros))
          {
            if ( substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros%23, 1) == $letra && strlen($letra) == 1 && strlen ($numeros) == 8 )
            {
             $verdad=true;
            }else{
             $verdad=false;
            }
          }
          else
          {
          $verdad=false;
          }

		   if ( !$verdad ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($dni))
                ->setCode(Blank::NOT_BLANK_ERROR)
                ->addViolation();
        }

    }
}
