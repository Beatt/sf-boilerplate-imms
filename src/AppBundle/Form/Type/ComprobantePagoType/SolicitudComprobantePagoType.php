<?php

namespace AppBundle\Form\Type\ComprobantePagoType;

use AppBundle\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudComprobantePagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pagos', CollectionType::class, [
                'entry_type' => PagoComprobanteType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
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
