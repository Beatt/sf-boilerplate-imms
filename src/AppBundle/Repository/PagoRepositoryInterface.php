<?php

namespace AppBundle\Repository;

<<<<<<< HEAD
use Doctrine\Common\Persistence\ObjectRepository;

interface PagoRepositoryInterface extends ObjectRepository
{
    function getAllPagosByRequest($id);
=======
use AppBundle\Entity\Pago;

interface PagoRepositoryInterface
{
    public function getComprobante($referenciaBancaria);
    public function save(Pago $pago);
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
}
