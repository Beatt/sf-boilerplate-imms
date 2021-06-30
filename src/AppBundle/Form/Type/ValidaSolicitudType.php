<?php


namespace AppBundle\Form\Type;

use AppBundle\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidaSolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('validado')
            /*->add('camposClinicos', CollectionType::class, [
                'entry_type' => ValidaMontoCampoClinicoType::class,
                'required'   => true,
                'by_reference' => true,
                'mapped' => true
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => Solicitud::class,
            'csrf_protection' => false,
        ]);
    }


    public function getBlockPrefix()
    {
        return 'solicitud';
    }
}