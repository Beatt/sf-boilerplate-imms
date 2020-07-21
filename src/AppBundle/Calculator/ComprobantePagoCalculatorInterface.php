<?php

namespace AppBundle\Calculator;

use AppBundle\Entity\Pago;

interface ComprobantePagoCalculatorInterface
{
    public function getMontoAPagar(Pago $pago);
}
