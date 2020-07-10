<?php

namespace AppBundle\Repository\IE\DetalleSolicitud;

use AppBundle\ObjectValues\SolicitudId;

interface DetalleSolicitud
{
    public function detalleBySolicitud(SolicitudId $solicitudId);
}
