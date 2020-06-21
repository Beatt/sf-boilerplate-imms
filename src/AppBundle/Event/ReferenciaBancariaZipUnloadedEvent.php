<?php

namespace AppBundle\Event;

use AppBundle\Entity\Solicitud;
use Symfony\Component\EventDispatcher\Event;

class ReferenciaBancariaZipUnloadedEvent extends Event
{
    const NAME = 'referencia_bancaria_zip.unloaded';

    private $solicitud;

    public function __construct(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud;
    }

    /**
     * @return Solicitud
     */
    public function getSolicitud()
    {
        return $this->solicitud;
    }
}
