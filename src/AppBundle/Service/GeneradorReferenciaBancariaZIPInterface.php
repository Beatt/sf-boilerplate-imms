<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;

interface GeneradorReferenciaBancariaZIPInterface
{
    public function generarZipResponse(Solicitud $solicitud);
}
