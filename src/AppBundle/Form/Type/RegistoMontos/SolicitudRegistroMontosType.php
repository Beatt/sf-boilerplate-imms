<?php

namespace AppBundle\Form\Type\RegistoMontos;

use AppBundle\Entity\Solicitud;
use AppBundle\Form\Type\ValidacionMontos\MontoCarreraValidacionMontosType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudRegistroMontosType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('camposClinicos', CollectionType::class, [
        'entry_type' => CampoClinicoRegistroMontosType::class,

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
