<?php

namespace AppBundle\Form\Type\RegistoMontos;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Solicitud;
use AppBundle\Form\Type\ValidacionMontos\MontoCarreraValidacionMontosType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampoClinicoRegistroMontosType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('montoCarrera',
        MontoCarreraValidacionMontosType::class);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    return $resolver->setDefaults([
      'data_class' => CampoClinico::class,
      'csrf_protection' => false,
    ]);
  }
}
