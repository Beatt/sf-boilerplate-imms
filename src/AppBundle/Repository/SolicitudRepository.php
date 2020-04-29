<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SolicitudRepository extends EntityRepository implements SolicitudRepositoryInterface
{
    public function getAllSolicitudesByInstitucion($id, $tipoPago, $estatus, $offset, $search = null)
    {
        $queryBuilder = $this->createQueryBuilder('solicitud')
            ->join('solicitud.camposClinicos', 'campos_clinicos')
            ->join('campos_clinicos.convenio', 'convenio')
            ->where('convenio.institucion = :id')
            ->andWhere("solicitud.tipoPago = :tipoPago")
            ->setParameters([
                'id' => $id,
                'tipoPago' => $tipoPago
            ])
        ;

        if($estatus != 'null') {
            $queryBuilder = $queryBuilder
                ->andWhere('campos_clinicos.estatus = :estatus')
                ->setParameter('estatus', $estatus);
        }

        if($search !== null && $search !== '') {
            $queryBuilder = $queryBuilder
                ->andWhere("LOWER(solicitud.noSolicitud) LIKE LOWER(:search)")
                ->orWhere("date_format(solicitud.fecha, 'dd/mm/YYYY') LIKE :search")
                ->setParameter('search', '%' . $search . '%')
            ;
        }

        $query = $queryBuilder
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult(($offset-1) * self::PAGINATOR_PER_PAGE)
            ->orderBy('solicitud.fecha', 'DESC')
            ->getQuery();

        return new Paginator($query);
    }
}
