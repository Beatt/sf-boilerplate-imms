<?php


namespace AppBundle\Form\Type;

use AppBundle\Entity\MontoCarrera;
use AppBundle\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SolicitudMontoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('montosCarrera', CollectionType::class, [
            'entry_type' => MontoCarreraType::class,
            'entry_options' => ['label' => false]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => Solicitud::class,
            'csrf_protection' => false,
        ]);
    }
}