<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UnidadRepository extends EntityRepository
{
    public function getAllUnidadesByDelegacion($delegacion_id)
    {
        return $this->createQueryBuilder('unidad')
//            ->where('delegacion_id = :delegacion_id')
//            ->setParameter('delegacion_id', $delegacion_id)
            ->getQuery()
            ->getResult();
    }
}