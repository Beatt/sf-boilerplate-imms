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
            ->join('campos_clinicos.convenio', 'convenio')
            ->join('convenio.institucion', 'institucion');
        if(isset($filters['no_solicitud']) && $filters['no_solicitud']){
            $queryBuilder->where('solicitud.noSolicitud like :no_solicitud')
                ->setParameter('no_solicitud', '%'.strtoupper($filters['no_solicitud']).'%');
        }
        if($delegacion_id){
            $queryBuilder->andWhere('convenio.delegacion = :delegacion_id')
                ->setParameter('delegacion_id', $delegacion_id)
            ;
        }

        if(isset($filters['institucion']) && $filters['institucion']){
            $queryBuilder->andWhere('upper(unaccent(institucion.nombre)) like UPPER(unaccent(:institucion))')
                ->setParameter('institucion', '%'.$filters['institucion'].'%');
        }

        if(isset($filters['fecha']) && $filters['fecha']){
            $queryBuilder->andWhere('solicitud.fecha = :fecha')
                ->setParameter('fecha', $filters['fecha']);
        }

        $qb2 = clone $queryBuilder;

        return ['data' => $queryBuilder->orderBy('solicitud.id', 'DESC')->setMaxResults($perPage)
            ->setFirstResult(($offset-1) * $perPage)->getQuery()
            ->getResult(),
            'total' => $qb2->select('COUNT(solicitud.id)')->getQuery()->getSingleScalarResult()
        ];
    }

    public function getSolicitudesPagadas($perPage = 10, $offset = 1, $filters = [])
    {
        $queryBuilder = $this->createQueryBuilder('solicitud')
            ->innerJoin('solicitud.pagos', 'pago')
            ->join('solicitud.camposClinicos', 'campos_clinicos')
            ->join('campos_clinicos.convenio', 'convenio')
            ->join('convenio.institucion', 'institucion')
            ->join('convenio.delegacion', 'delegacion')
            ->leftJoin('pago.factura', 'factura');

        if(isset($filters['institucion']) && $filters['institucion']){
            $queryBuilder->andWhere('upper(unaccent(institucion.nombre)) like UPPER(unaccent(:institucion))')
                ->setParameter('institucion', '%'.$filters['institucion'].'%');
        }

        if(isset($filters['delegacion']) && $filters['delegacion']){
            $queryBuilder->andWhere('upper(unaccent(delegacion.nombre)) like UPPER(unaccent(:delegacion))')
                ->setParameter('delegacion', '%'.$filters['delegacion'].'%');
        }

        if(isset($filters['referencia']) && $filters['referencia']){
            $queryBuilder->andWhere('upper(unaccent(pago.referenciaBancaria)) like UPPER(unaccent(:referencia))')
                ->setParameter('referencia', '%'.$filters['referencia'].'%');
        }

        if(isset($filters['factura']) && $filters['factura']){
            $queryBuilder->andWhere('upper(unaccent(factura.folio)) like UPPER(unaccent(:factura))')
                ->setParameter('factura', '%'.$filters['factura'].'%');
        }

        if(isset($filters['no_solicitud']) && $filters['no_solicitud']){
            $queryBuilder->andWhere('upper(unaccent(solicitud.noSolicitud)) like UPPER(unaccent(:no_solicitud))')
                ->setParameter('no_solicitud', '%'.$filters['no_solicitud'].'%');
        }


        $queryBuilder->distinct();
        $qb2 = clone $queryBuilder;

        return ['data' => $queryBuilder->orderBy('solicitud.id', 'DESC')->setMaxResults($perPage)
            ->setFirstResult(($offset-1) * $perPage)->getQuery()
            ->getResult(),
            'total' => $qb2->select('COUNT(solicitud.id)')->getQuery()->getSingleScalarResult()
        ];

    }
}
