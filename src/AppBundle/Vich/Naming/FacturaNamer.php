<?php

namespace AppBundle\Vich\Naming;

use AppBundle\Entity\Factura;
use Carbon\Carbon;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class FacturaNamer implements NamerInterface
{
    /**
     * @inheritDoc
     * @var Factura $object
     */
    public function name($object, PropertyMapping $mapping)
    {
        return  sprintf(
            '%s_%s-factura.%s',
            $object->getFolio(),
            Carbon::now()->format('Y-m-d_H_i_s'),
            $object->getZipFile()->guessExtension()
        );
    }
}
