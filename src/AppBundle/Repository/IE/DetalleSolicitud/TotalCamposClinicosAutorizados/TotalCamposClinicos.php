<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\TotalCamposClinicosAutorizados;

use AppBundle\ObjectValues\SolicitudId;

interface TotalCamposClinicos
{
    public function totalCamposClinicosAutorizados(SolicitudId $solicitudId);
}
