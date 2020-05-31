<?php

namespace AppBundle\Event;

use AppBundle\Entity\Solicitud;
use Symfony\Component\EventDispatcher\Event;

class BankReferencesCreatedEvent extends Event
{
    const NAME = 'bank_references.created';

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
