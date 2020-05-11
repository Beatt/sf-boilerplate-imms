<?php


namespace AppBundle\Form\Type;


use AppBundle\Entity\MontoCarrera;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MontoCarreraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('montoColegiatura')
            ->add('montoInscripcion')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => MontoCarrera::class,
            'csrf_protection' => false,
        ]);
    }
}