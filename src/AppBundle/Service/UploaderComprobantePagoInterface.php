<?php

namespace AppBundle\Service;

use AppBundle\Entity\Pago;

interface UploaderComprobantePagoInterface
{
    public function update(Pago $pago);
}
