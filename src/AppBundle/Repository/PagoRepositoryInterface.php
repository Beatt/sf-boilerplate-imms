<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Pago;

interface PagoRepositoryInterface
{
    public function getComprobante($referenciaBancaria);
    public function save(Pago $pago);
}
