<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Pago;
use AppBundle\Entity\Factura;
use AppBundle\Form\Type\FacturaType;
use Doctrine\DBAL\Types\DateType as TypesDateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('factura', FacturaType::class)
            ->add('requiereFactura')
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