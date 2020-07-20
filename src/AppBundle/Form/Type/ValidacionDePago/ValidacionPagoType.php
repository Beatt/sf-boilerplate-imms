<?php

namespace AppBundle\Form\Type\ValidacionDePago;

use AppBundle\Entity\Pago;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidacionPagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('monto')
            ->add('fechaPago', DateType::class, [
                'widget' => 'single_text',
                'format' => 'YYYY-MM-dd'
            ])
            ->add('requiereFactura', TextType::class)
            ->add('observaciones')
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var Pago $pago */
            $pago = $event->getData();
            $form = $event->getForm();

            $pago->setRequiereFactura(
                $form->get('requiereFactura')->getViewData() === '1' ? 1 : 0
            );
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => Pago::class,
            'csrf_protection' => false
        ]);
    }
}
