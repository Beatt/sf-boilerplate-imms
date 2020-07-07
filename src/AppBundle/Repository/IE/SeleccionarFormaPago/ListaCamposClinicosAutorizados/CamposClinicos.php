<?php

namespace AppBundle\Repository\IE\SeleccionarFormaPago\ListaCamposClinicosAutorizados;

use AppBundle\ObjectValues\SolicitudId;

interface CamposClinicos
{
    public function listaCamposClinicosAutorizados(SolicitudId $solicitudId);
}
