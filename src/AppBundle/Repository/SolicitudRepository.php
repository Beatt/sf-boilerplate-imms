<?php

namespace AppBundle\Repository;

use AppBundle\Entity\SolicitudInterface;
use Doctrine\ORM\EntityRepository;

class SolicitudRepository extends EntityRepository implements SolicitudRepositoryInterface
{
    public function getAllSolicitudesByInstitucion($id, $tipoPago, $estatus, $orderBy, $search = null)
    {
        $queryBuilder = $this->createQueryBuilder('solicitud')
            ->join('solicitud.camposClinicos', 'campos_clinicos')
            ->join('campos_clinicos.convenio', 'convenio');

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

        if($estatus !== 'null' && $estatus !== '') {
            $queryBuilder = $queryBuilder
                ->andWhere("solicitud.estatus = :estatus AND solicitud.estatus != :estatusCreada")
                ->setParameter('estatusCreada', SolicitudInterface::CREADA)
                ->setParameter('estatus', $estatus);
        } else {
            $queryBuilder = $queryBuilder
                ->andWhere("solicitud.estatus != :estatusCreada")
                ->setParameter('estatusCreada', SolicitudInterface::CREADA);
        }

        $queryBuilder = $queryBuilder
            ->andWhere('convenio.institucion = :id')
            ->setParameter('id', $id)
            ->orderBy('solicitud.fecha', 'DESC');

        if(
            $orderBy === self::FILTER_FOR_ORDERING_NO_SOLICITUD_MAYOR_A_MENOR ||
            $orderBy === self::FILTER_FOR_ORDERING_NO_SOLICITUD_MENOR_A_MAYOR
        ) {

            $order = $orderBy === self::FILTER_FOR_ORDERING_NO_SOLICITUD_MAYOR_A_MENOR ?
                'DESC' : 'ASC';

            $queryBuilder = $queryBuilder
                ->orderBy('solicitud.noSolicitud', $order);
        }

        if(
            $orderBy === self::FILTER_FOR_ORDERING_FECHA_DE_SOLICITUD_MAS_ANTIGUA ||
            $orderBy === self::FILTER_FOR_ORDERING_FECHA_DE_SOLICITUD_MAS_RECIENTE
        ) {

            $order = $orderBy === self::FILTER_FOR_ORDERING_FECHA_DE_SOLICITUD_MAS_ANTIGUA ?
                'ASC' : 'DESC';

            $queryBuilder = $queryBuilder
                ->orderBy('solicitud.fecha', $order);
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
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

        return ['data' => $queryBuilder->distinct()->orderBy('solicitud.id', 'DESC')->setMaxResults($perPage)
            ->setFirstResult(($offset-1) * $perPage)->getQuery()
            ->getResult(),
            'total' => $qb2->select('COUNT(distinct solicitud.id)')->getQuery()->getSingleScalarResult()
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

        return ['data' => $queryBuilder->distinct()->orderBy('solicitud.id', 'DESC')->setMaxResults($perPage)
            ->setFirstResult(($offset-1) * $perPage)->getQuery()
            ->getResult(),
            'total' => $qb2->select('COUNT(distinct solicitud.id)')->getQuery()->getSingleScalarResult()
        ];

    }

    public function getSolicitudesByInstitucion($id)
    {
        return $this->createQueryBuilder('solicitud')
            ->join('solicitud.camposClinicos', 'campos_clinicos')
            ->join('campos_clinicos.convenio', 'convenio')
            ->Where('convenio.institucion = :id')
            ->setParameter('id', $id)
            ->orderBy('solicitud.fecha', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
