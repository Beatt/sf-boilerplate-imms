<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UnidadRepository extends EntityRepository
{
    public function getAllUnidadesByDelegacion($delegacion_id = 1, $include_umaes=true)
    {
        $qb = $this->createQueryBuilder('unidad')
            ->innerJoin('unidad.delegacion', 'delegacion')
            //->innerJoin('unidad.tipoUnidad', 'tipo')
        ;

        $qb->where('delegacion.id = :delegacion_id')
            ->andWhere($qb->expr()->orX(
               $qb->expr()->isNotNull('unidad.tipoUnidad'),
                $qb->expr()->eq('unidad.esUmae', 'true')
        ))
            ->setParameter('delegacion_id', $delegacion_id)
            ->orderBy('unidad.nombre');

        if (!$include_umaes) {
          $qb->andWhere('unidad.esUmae = false');
        }

        return $qb->getQuery()
            ->getResult();
    }
}