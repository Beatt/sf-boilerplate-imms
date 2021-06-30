<?php


namespace AppBundle\Form\Type;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Solicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidaMontoCampoClinicoType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('observaciones')
      ->add('montoCarrera', ValidaMontoCarreraType::class, [
        'required'   => true
      ])
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