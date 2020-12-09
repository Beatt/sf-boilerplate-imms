<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UnidadRepository extends EntityRepository
{
    public function getAllUnidadesByDelegacion($delegacion_id = 1)
    {
        $qb = $this->createQueryBuilder('unidad')
            ->innerJoin('unidad.delegacion', 'delegacion')
            ->innerJoin('unidad.tipoUnidad', 'tipo');

        $qb->where('delegacion.id = :delegacion_id')
            ->andWhere($qb->expr()->orX(
            $qb->expr()->like('tipo.nombre', "'UM%'"),
            $qb->expr()->like('tipo.nombre', "'HG%'"),
            $qb->expr()->like('tipo.grupoTipo', "'UMAE'")
        ))
            ->setParameter('delegacion_id', $delegacion_id)
            ->orderBy('unidad.nombre');

        return $qb->getQuery()
            ->getResult();
    }
}