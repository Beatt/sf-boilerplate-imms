<?php

namespace AppBundle\Form\Type\ValidacionMontos;

use AppBundle\Entity\MontoCarrera;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MontoCarreraValidacionMontosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montoInscripcion')
            ->add('montoColegiatura')
            ->add('carrera')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => MontoCarrera::class,
            'csrf_protection' => false
        ]);
    }
}
