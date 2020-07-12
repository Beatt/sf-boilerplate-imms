<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple\Expediente;

use AppBundle\ObjectValues\SolicitudId;

interface Expediente
{
    public function expedienteBySolicitud(SolicitudId $solicitudId);
}
