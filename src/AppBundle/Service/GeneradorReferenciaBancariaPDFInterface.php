<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;

interface GeneradorReferenciaBancariaPDFInterface
{
    public function generarPDF(Solicitud $solicitud, $directoryOutput);
}
