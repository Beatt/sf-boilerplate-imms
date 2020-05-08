<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Pago;
use Doctrine\ORM\EntityRepository;

class PagoRepository extends EntityRepository implements PagoRepositoryInterface
{
    public function getComprobante($referenciaBancaria)
    {
        return $this->findOneByReferenciaBancaria($referenciaBancaria);
    }

    public function save(Pago $pago)
    {
        $this->_em->persist($pago);
        $this->_em->flush();
    }
}
