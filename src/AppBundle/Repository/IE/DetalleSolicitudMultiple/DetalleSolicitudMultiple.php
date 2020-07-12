<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple;

use AppBundle\ObjectValues\SolicitudId;

interface DetalleSolicitudMultiple
{
    public function getDetalleBySolicitud(SolicitudId $solicitudId);
}
