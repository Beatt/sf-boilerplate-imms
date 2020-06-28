<?php

namespace AppBundle\Repository\GetExistSolicitud;

final class Solicitud
{
    private $existSolicitud;

    public function __construct($existSolicitud)
    {
        $this->existSolicitud = $existSolicitud;
    }

    /**
     * @return bool
     */
    public function isSolicitudOfCurrentUser()
    {
        return $this->existSolicitud;
    }
}
