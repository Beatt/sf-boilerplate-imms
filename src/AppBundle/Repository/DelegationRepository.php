<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DelegationRepository extends EntityRepository
{
    public function getAllDelegacionesNotNullRegion() {
        return $this->createQueryBuilder('d')
            ->where('d.region IS NOT NULL')
            ->getQuery()
            ->getResult();
    }
}
