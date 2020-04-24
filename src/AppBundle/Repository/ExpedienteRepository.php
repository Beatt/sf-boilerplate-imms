<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ExpedienteRepository extends EntityRepository implements ExpedienteRepositoryInterface
{
    /**
     * @param $id
     * @return array
     */
    public function getAllExpedientesByRequest($id)
    {
        return $this->createQueryBuilder('expediente')
            ->where('expediente.solicitud = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
