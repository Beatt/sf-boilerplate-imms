<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SolicitudRepository extends EntityRepository implements SolicitudRepositoryInterface
{
    public function getAllSolicitudesById($id, $offset, $search = null)
    {
        $queryBuilder = $this->createQueryBuilder('solicitud')
            ->select('solicitud')
            ->where('solicitud.id = :id')
            ->setParameter('id', $id);

        if($search !== null) {
            $queryBuilder = $queryBuilder
                ->andWhere("solicitud.estatus LIKE :search")
                ->orWhere("solicitud.noSolicitud LIKE :search")
                ->orWhere("date_format(solicitud.fecha, 'dd/mm/YYYY') LIKE :search")
                ->setParameter('search', $search);
        }

        $query = $queryBuilder
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->orderBy('solicitud.fecha', 'DESC')
            ->getQuery();

        return new Paginator($query);
    }

}
