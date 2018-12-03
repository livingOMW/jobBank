<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Alumno;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AlumnoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', TextType::class)
        ->add('apellido1', TextType::class)
        ->add('apellido2', TextType::class, [
            'required'   => false,
              ])
        ->add('dni', TextType::class, [
                'label' => 'DNI o NIE',
            ])
        ->add('email', EmailType::class)

        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Contraseña incorrecta.',
            'first_options' => [
                'label' => 'Contraseña',
            ],
            'second_options' => [
                'label' => 'Repite Contraseña'
            ]
        ])
        ->add('curso', EntityType::class,
                      array('class' => 'AppBundle:Curso',
                            'choice_label' => 'name',
                            'invalid_message' => 'Curso incorrecto.')
             )
        ->add('nre',IntegerType::class, [
                'label' => 'NRE',
                'invalid_message' => 'Número NRE incorrecto.',
            ])
        ->add('exp',IntegerType::class, [
                'label' => 'Número de expediente',
                'invalid_message' => 'Número de expediente incorrecto.',
                'required'   => false,
            ])
        ->add('telefono', IntegerType::class, [
                'invalid_message' => 'Teléfono incorrecto.',
                'label' => 'Teléfono',
                'required'   => false,
            ])
        ->add('anyoPromocion', IntegerType::class, [
                'invalid_message' => 'Año de promoción incorrecto.',
                'label' => 'Año de promoción',
            ])
        ->add('registrar', SubmitType::class,[
            'attr' => [
                'class' => 'btn btn-success btn-block col-sm-12'
            ]
        ])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Alumno::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_alumno';
    }


}
