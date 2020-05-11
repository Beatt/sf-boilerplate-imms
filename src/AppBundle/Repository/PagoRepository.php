<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PagoRepository extends EntityRepository implements PagoRepositoryInterface
{
    /**
     * @param $id
     * @return array
     */
    public function getAllPagosByRequest($id)
    {
        return $this->createQueryBuilder('pago')
            ->where('pago.solicitud = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
