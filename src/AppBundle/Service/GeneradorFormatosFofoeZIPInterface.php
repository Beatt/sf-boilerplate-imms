<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;

interface GeneradorFormatosFofoeZIPInterface
{
    public function generarPDF(Solicitud $solicitud);
}
