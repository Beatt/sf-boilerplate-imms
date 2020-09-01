<?php

namespace AppBundle\Vich\Naming;

use AppBundle\Entity\Solicitud;
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
            '%s_comprobante-inscripcion.%s',
            $object->getNoSolicitud(),
            $object->getUrlArchivoFile()->guessExtension()
        );
    }
}
