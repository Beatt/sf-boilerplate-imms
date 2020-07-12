<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple\ListaCamposClinicos;

use AppBundle\ObjectValues\SolicitudId;

interface CamposClinicos
{
    public function listaCamposClinicosBySolicitud(SolicitudId $solicitudId);
}
