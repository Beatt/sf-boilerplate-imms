<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos;

use AppBundle\ObjectValues\SolicitudId;

interface CamposClinicos
{
    public function listaCamposClinicosBySolicitud(SolicitudId $solicitudId);
}
