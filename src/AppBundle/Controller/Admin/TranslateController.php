<?php

namespace AppBundle\Controller\Admin;

use AppBundle\DTO\TraductorDTO;
use AppBundle\Entity\Traductor;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Form\Form;

class TranslateController extends BaseAdminController
{

    /**
     * @param  Traductor $traductor
     */
    protected function prePersistEntity($traductor)
    {
        $jsonContent = $this->get('serializer')->serialize(
            $traductor->getTraductorDTO(),
            'json'
        );
        $traductor->setTextos($jsonContent);
    }

    /**
     * @param Traductor $traductor
     * @return Form
     */
    protected function createEditForm($traductor, array $entityProperties)
    {
        $traductorDTO = $this->get('serializer')->deserialize(
            $traductor->getTextos(),
            TraductorDTO::class,
            'json'
        );
        $traductor->setTraductorDTO($traductorDTO);

        return parent::createEditForm($traductor, $entityProperties); // TODO: Change the autogenerated stub
    }
}