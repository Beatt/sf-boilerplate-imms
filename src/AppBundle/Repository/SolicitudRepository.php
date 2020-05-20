<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SolicitudRepository extends EntityRepository implements SolicitudRepositoryInterface
{
    public function getAllSolicitudesByInstitucion($id, $tipoPago, $offset, $search = null)
    {
        $queryBuilder = $this->createQueryBuilder('solicitud')
            ->join('solicitud.camposClinicos', 'campos_clinicos')
            ->join('campos_clinicos.convenio', 'convenio')
            ->where('convenio.institucion = :id')
            ->setParameter('id', $id);

        if ($tipoPago !== 'null' && $tipoPago !== '') {
            $queryBuilder = $queryBuilder
                ->andWhere("solicitud.tipoPago = :tipoPago")
                ->setParameter('tipoPago', $tipoPago);
        }

        if ($search !== null && $search !== '') {
            $queryBuilder = $queryBuilder
                ->andWhere("LOWER(solicitud.noSolicitud) LIKE LOWER(:search)")
                ->orWhere("date_format(solicitud.fecha, 'dd/mm/YYYY') LIKE :search")
                ->setParameter('search', '%' . $search . '%');
        }

        $query = $queryBuilder
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult(($offset - 1) * self::PAGINATOR_PER_PAGE)
            ->orderBy('solicitud.fecha', 'DESC')
            ->getQuery();

        return new Paginator($query);
    }

    public function getAllSolicitudesByDelegacion($delegacion_id = null, $perPage = 10, $offset = 1, $filters = [])
    {

        $queryBuilder = $this->createQueryBuilder('solicitud')
            ->join('solicitud.camposClinicos', 'campos_clinicos')
            ->join('campos_clinicos.convenio', 'convenio');
        if(isset($filters['no_solicitud']) && $filters['no_solicitud']){
            $queryBuilder->where('solicitud.noSolicitud like :no_solicitud')
                ->setParameter('no_solicitud', '%'.$filters['no_solicitud'].'%');
        }
        if($delegacion_id){
            $queryBuilder->andWhere('convenio.delegacion = :delegacion_id')
                ->setParameter('delegacion_id', $delegacion_id)
            ;
        }

        $queryBuilder->setMaxResults($perPage)
            ->setFirstResult(($offset-1) * $perPage);

        return $queryBuilder->getQuery()
            ->getResult();
    }
}
