<?php

namespace AppBundle\Service;

use AppBundle\Entity\Institucion;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class InstitucionVichNamer implements NamerInterface
{
    /**
     * @inheritDoc
     * @var Institucion $object
     */
    public function name($object, PropertyMapping $mapping)
    {
        return $object->getRfc() . '.' . $object->getCedulaFile()->guessExtension();
    }
}
