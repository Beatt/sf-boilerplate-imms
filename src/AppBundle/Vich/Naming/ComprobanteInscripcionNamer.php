<?php

namespace AppBundle\Vich\Naming;

use AppBundle\Entity\Solicitud;
use Carbon\Carbon;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class ComprobanteInscripcionNamer implements NamerInterface
{
    /**
     * @inheritDoc
     * @var Solicitud $object
     */
    public function name($object, PropertyMapping $mapping)
    {
        return  sprintf(
            '%s-inscripcion_comprobante.%s',
            Carbon::now()->format('dmY:His'),
            $object->getUrlArchivoFile()->guessExtension()
        );
    }
}
