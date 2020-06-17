<?php

namespace AppBundle\Service;

use AppBundle\Entity\ComprobantePagoInterface;

interface UploaderComprobantePagoInterface
{
    public function update(ComprobantePagoInterface $pago);
}
