<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Institucion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class InstitucionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('rfc')
            ->add('direccion')
            ->add('correo')
            ->add('telefono')
            ->add('fax')
            ->add('sitioWeb')
            ->add('cedulaFile', VichFileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => Institucion::class
        ]);
    }
}
