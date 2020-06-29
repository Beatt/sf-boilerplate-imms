<?php

namespace AppBundle\Repository\GetExistSolicitud;

use AppBundle\ObjectValues\SolicitudId;
use AppBundle\ObjectValues\UsuarioId;

interface GetExistSolicitud
{
    public function ofUsuario(SolicitudId $solicitudId, UsuarioId $institucionId);
}
