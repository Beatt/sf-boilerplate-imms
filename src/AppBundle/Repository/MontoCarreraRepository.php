<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MontoCarreraRepository extends EntityRepository implements MontoCarreraRepositoryInterface
{
    function getAllMontosCarreraByRequest($id){
        return $this->createQueryBuilder('montoCarrera')
        ->where('montoCarrera.solicitud = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult();
    }
}
