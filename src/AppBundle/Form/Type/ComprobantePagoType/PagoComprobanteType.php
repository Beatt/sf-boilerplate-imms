<?php

namespace AppBundle\Form\Type\ComprobantePagoType;

use AppBundle\Entity\Pago;
use Doctrine\DBAL\Types\DateType as TypesDateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PagoComprobanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('monto')
            ->add('fechaPago', DateType::class)
            ->add('referenciaBancaria')
            ->add('solicitud')
            ->add('comprobantePagoFile', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => Pago::class,
            'csrf_protection' => false
        ]);
    }
}
