<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UnidadRepository extends EntityRepository
{
    public function getAllUnidadesByDelegacion($delegacion_id = 1)
    {
        return $this->createQueryBuilder('unidad')
            ->innerJoin('unidad.delegacion', 'delegacion')
            ->where('delegacion.id = :delegacion_id')
            ->setParameter('delegacion_id', $delegacion_id)
            ->orderBy('unidad.nombre')
            ->getQuery()
            ->getResult();
    }
}