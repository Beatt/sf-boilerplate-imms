<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

use AppBundle\ObjectValues\SolicitudId;

interface Expediente
{
    public function expedienteBySolicitud(SolicitudId $solicitudId);
}
