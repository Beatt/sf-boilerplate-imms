<?php


namespace AppBundle\Form\Type;


use AppBundle\Entity\CampoClinico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampoClinicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('solicitud')
            ->add('convenio')
            ->add('fechaInicial')
            ->add('fechaFinal')
            ->add('lugaresSolicitados')
            ->add('lugaresAutorizados')
            ->add('unidad')
            ->add('horario')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => CampoClinico::class,
            'csrf_protection' => false,
        ]);
    }
}