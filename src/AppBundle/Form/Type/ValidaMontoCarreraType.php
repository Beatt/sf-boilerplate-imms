<?php


namespace AppBundle\Form\Type;

use AppBundle\Entity\MontoCarrera;
use AppBundle\Form\Type\ValidacionMontos\DescuentoMontoType;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidaMontoCarreraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montoInscripcion')
            ->add('montoColegiatura')
            ->add('descuentos',
                CollectionType::class, [
                    'entry_type' => DescuentoMontoType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => MontoCarrera::class,
            'csrf_protection' => false,
        ]);
    }
}