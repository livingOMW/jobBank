<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\Administrador;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class AdministradorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->add('nombre')
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
              ->add('registrar', SubmitType::class,[
                  'attr' => [
                      'class' => 'btn btn-success btn-block '
                  ]
              ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Administrador'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_administrador';
    }


}
