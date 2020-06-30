<?php

namespace AppBundle\Service;

use AppBundle\Entity\Pago;

interface GeneradorReferenciaBancariaInterface
{
    public function makeReferenciaBancaria(Pago $pago, $id);

}
