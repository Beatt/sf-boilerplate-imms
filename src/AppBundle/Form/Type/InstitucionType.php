<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Institucion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitucionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rfc')
            ->add('direccion')
            ->add('correo')
            ->add('telefono')
            ->add('fax')
            ->add('sitioWeb')
<<<<<<< HEAD
            ->add('representante')
=======
>>>>>>> b2ae1368e1337dc3f3d1bf1cac222c68e138fb70
            ->add('cedulaFile', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => Institucion::class,
            'csrf_protection' => false,
        ]);
    }
}
