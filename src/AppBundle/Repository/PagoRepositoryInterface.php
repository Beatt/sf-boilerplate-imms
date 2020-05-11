<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Pago;

interface PagoRepositoryInterface extends ObjectRepository
{
    function getAllPagosByRequest($id);
    public function getComprobante($referenciaBancaria);
    public function save(Pago $pago);
}
