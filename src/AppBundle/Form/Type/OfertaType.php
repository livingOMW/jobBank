<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Curso;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfertaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cursos', EntityType::class, [
                'class' => Curso::class,
                'choice_label' => 'name',
                'multiple'     => true,
                'expanded'     => true,
                'required'     => false
            ])
            ->add('titulo', TextType::class, [
                'required' => true, 'label'=>'Título',
            ])
            ->add('texto', TextareaType::class, [
                'attr' => ['rows' => '10'],
                'required' => false,
            ])
            ->add('archivos', ArchivoType::class, [
                'label' => false,
            ])

            ->add('Enviar', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-lg btn-success btn-block'
                ]
            ])

        ;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Oferta'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_oferta';
    }


}
