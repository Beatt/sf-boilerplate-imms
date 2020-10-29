<?php


namespace AppBundle\Form\Type\ValidacionMontos;

use AppBundle\Entity\DescuentoMonto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DescuentoMontoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numAlumnos')
            ->add('descuentoInscripcion')
            ->add('descuentoColegiatura');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => DescuentoMonto::class,
            'csrf_protection' => false,
        ]);
    }
}