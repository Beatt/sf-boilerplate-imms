<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SolicitudRepository extends EntityRepository implements SolicitudRepositoryInterface
{
    public function getAllSolicitudesByInstitucion($id, $offset, $search = null)
    {
        $queryBuilder = $this->createQueryBuilder('solicitud')
            ->join('solicitud.camposClinicos', 'campos_clinicos')
            ->join('campos_clinicos.convenio', 'convenio')
            ->where('convenio.institucion = :id')
            ->setParameter('id', $id);

        if($search !== null) {
            $queryBuilder = $queryBuilder
                ->andWhere("LOWER(solicitud.estatus) LIKE LOWER(:search)")
                ->orWhere("LOWER(solicitud.noSolicitud) LIKE LOWER(:search)")
                ->orWhere("date_format(solicitud.fecha, 'dd/mm/YYYY') LIKE :search")
                ->setParameter('search', '%' . $search . '%');
        }

        $query = $queryBuilder
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->orderBy('solicitud.fecha', 'DESC')
            ->getQuery();

        return new Paginator($query);
    }

}
