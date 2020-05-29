<?php

namespace AppBundle\Service;

use AppBundle\Entity\Solicitud;

interface ProcesadorFormaPagoInterface
{
    public function procesar(Solicitud $solicitud);
}
