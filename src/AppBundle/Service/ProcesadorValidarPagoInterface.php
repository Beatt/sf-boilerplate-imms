<?php

namespace AppBundle\Service;

use AppBundle\Entity\Pago;

interface ProcesadorValidarPagoInterface
{
    public function procesar(Pago $pago);
}
