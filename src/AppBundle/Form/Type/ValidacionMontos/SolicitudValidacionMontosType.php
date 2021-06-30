<?php

namespace AppBundle\Form\Type\ValidacionMontos;

use AppBundle\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudValidacionMontosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montosCarreras', CollectionType::class, [
                'entry_type' => MontoCarreraValidacionMontosType::class,
            ])
            ->add('urlArchivoFile', FileType::class)
            ->add('confirmacionOficioAdjunto')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => Solicitud::class,
            'csrf_protection' => false,
        ]);
    }
}
