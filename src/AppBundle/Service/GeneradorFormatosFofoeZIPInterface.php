<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;

interface GeneradorFormatosFofoeZIPInterface
{
    public function generarZipResponse(Solicitud $solicitud);
}
