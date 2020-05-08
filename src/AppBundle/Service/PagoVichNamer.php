<?php

namespace AppBundle\Service;

use AppBundle\Entity\Pago;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class PagoVichNamer implements NamerInterface
{
    /**
     * @inheritDoc
     * @var Pago $object
     */
    public function name($object, PropertyMapping $mapping)
    {
        return $object->getId() . '.' . $object->getComprobantePagoFile()->guessExtension();
    }
}
