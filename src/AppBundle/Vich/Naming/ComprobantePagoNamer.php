<?php

namespace AppBundle\Vich\Naming;

use AppBundle\Entity\Pago;
use Carbon\Carbon;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class ComprobantePagoNamer implements NamerInterface
{
    /**
     * @inheritDoc
     * @var Pago $object
     */
    public function name($object, PropertyMapping $mapping)
    {
        return  sprintf(
            '%s-comprobantepago.%s',
            Carbon::now()->format('dmY:His'),
            $object->getComprobantePagoFile()->guessExtension()
        );
    }
}
