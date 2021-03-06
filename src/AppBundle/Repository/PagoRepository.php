<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Pago;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PagoRepository extends EntityRepository implements PagoRepositoryInterface
{
    /**
     * @param $id
     * @return array
     */
    public function getAllPagosByRequest($id)
    {
        return $this->createQueryBuilder('pago')
            ->where('pago.solicitud = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function getComprobante($referenciaBancaria)
    {
        return $this->findOneByReferenciaBancaria($referenciaBancaria);
    }

    public function save(Pago $pago)
    {
        $this->_em->persist($pago);
        $this->_em->flush();
    }

    public static function createGetPagoByReferenciaBancariaCriteria($referenciaBancaria)
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('referenciaBancaria', $referenciaBancaria));
    }

    public function getPagosCampoClinicosBySolicitud($solicitud_id)
    {
        return $this->createQueryBuilder('pago')
            ->innerJoin('pago.solicitud', 'solicitud')
            ->innerJoin('solicitud.camposClinicos', 'campos_clinicos')
            ->where('solicitud.id = :solicitud_id')
            ->Andwhere('pago.referenciaBancaria = campos_clinicos.referenciaBancaria')
            ->setParameter('solicitud_id', $solicitud_id)
            ->getQuery()->getResult();
    }

    public function paginate($perPage = 10, $offset = 1, $filters = [])
    {
        $queryBuilder = $this->createQueryBuilder('pago')
            ->select(['pago', 'solicitud'])
            ->join('pago.solicitud', 'solicitud')
            ->join('solicitud.camposClinicos', 'campos_clinicos')
            ->join('campos_clinicos.convenio', 'convenio')
            ->join('convenio.institucion', 'institucion')
            ->join('convenio.delegacion', 'delegacion')
            ->leftJoin('pago.factura', 'factura');
            ;

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

        if(isset($filters['monto']) && $filters['monto'] && is_numeric($filters['monto'])){
            $queryBuilder->andWhere("concat(pago.monto,'')  like :monto")
                ->setParameter('monto', '%'.$filters['monto'].'%');
        }

        if(isset($filters['estado']) && $filters['estado']){
            switch ($filters['estado']) {
                case 'a':
                    $queryBuilder->andWhere('pago.validado is null');
                    break;
                case 'b':
                    $queryBuilder->andWhere('pago.validado = true');
                    break;
                case 'c':
                    $queryBuilder->andWhere('pago.validado = true AND pago.requiereFactura = true AND pago.factura is NULL');
                    break;
                case 'd':
                    $queryBuilder->andWhere('pago.validado = false');
                    break;
            }
        }

        if(!isset($filters['year']) || !$filters['year']) {
            $filters['year'] = Carbon::now()->format('Y');
        }
        $fecha_i = "{$filters['year']}-01-01";
        $fecha_f = "{$filters['year']}-12-31";
        $queryBuilder->andWhere('pago.fechaPago >= :fecha_i AND pago.fechaPago <= :fecha_f')
            ->setParameter('fecha_i', $fecha_i)
            ->setParameter('fecha_f', $fecha_f);


        $qb2 = clone $queryBuilder;

        if(isset($filters['orderby']) && $filters['orderby']) {
            switch ($filters['orderby']){
                case 'a':
                    $queryBuilder->orderBy('pago.fechaPago', 'DESC');
                    break;
                case 'b':
                    $queryBuilder->orderBy('pago.fechaPago', 'ASC');
                    break;
                case 'c':
                    $queryBuilder->orderBy('solicitud.noSolicitud', 'DESC');
                    break;
                case 'd':
                default:
                    $queryBuilder->orderBy('solicitud.noSolicitud', 'ASC');
                    break;
            }
        }else{
            $queryBuilder->orderBy('pago.id', 'DESC');
        }

        return ['data' => $queryBuilder
            ->distinct()
            ->setFirstResult(($offset-1) * $perPage)
            ->setMaxResults($perPage)
            ->getQuery()
            ->getResult(),
            'total' => $qb2->select('COUNT(distinct pago.id)')->getQuery()->getSingleScalarResult()
        ];
    }

    public function getComprobantesPagoByReferenciaBancaria($referenciaBancaria)
    {
        return $this->createQueryBuilder('pago')
            ->where('pago.referenciaBancaria = :referenciaBancaria')
            ->setParameter('referenciaBancaria', $referenciaBancaria)
            ->getQuery()
            ->getResult()
        ;
    }

    public static function getUltimoPagoByCriteria($referenciaBancaria)
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('referenciaBancaria', $referenciaBancaria))
            ->orderBy(['id' => Criteria::DESC])
            ->setFirstResult(0)
            ->setMaxResults(1)
        ;
    }

    public static function getPagosByReferenciaBancaria($referenciaBancaria)
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('referenciaBancaria', $referenciaBancaria));
    }

    public static function getPagosCargadosByReferenciaBancaria($referenciaBancaria)
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('referenciaBancaria', $referenciaBancaria))
            ->andWhere(Criteria::expr()->neq('comprobantePago', null));
    }
     public function getReporteIngresosMes($anio) {

      $selAnio = "to_char(pago.fechaPago, 'YYYY')";
      $selMes =  "to_char(pago.fechaPago, 'MM')";

       $qb = $this->createQueryBuilder('pago')
         ->select(array(
           "$selAnio as Anio",
           "$selMes as Mes",
           "sum(case when pago.validado = 'TRUE'
                then campos_clinicos.monto else 0 end) as ingVal",
           "sum(case when pago.validado IS NULL
                then campos_clinicos.monto else 0 end) as ingPend",
           "sum(pago.monto) as Total"
         ))
         ->innerJoin('pago.solicitud', 'solicitud')
         ->innerJoin('solicitud.camposClinicos', 'campos_clinicos')
         ->innerJoin('campos_clinicos.convenio', 'convenio');

       $qb->where(
           $qb->expr()->orX(
             'pago.referenciaBancaria = solicitud.referenciaBancaria',
             'pago.referenciaBancaria = campos_clinicos.referenciaBancaria'
           )
         );

       $qb->andWhere("$selAnio  = :anio")
         ->setParameter('anio', $anio);

       $qb->groupBy("Anio, Mes")
        ->orderBy('Anio', 'DESC')
       ->addOrderBy('Mes', 'ASC');

       return $qb->getQuery()->getResult();
     }


    /**
     * @param $id
     * @return array
     */
    public function getAllPagosByInstitucion($id)
    {
      return $this->createQueryBuilder('pago')
            ->innerJoin('pago.solicitud', 'solicitud')
            ->innerJoin('solicitud.camposClinicos', 'campos_clinicos')
            ->where('solicitud.id = :solicitud_id')
            ->Andwhere('pago.referenciaBancaria = campos_clinicos.referenciaBancaria')
            ->setParameter('solicitud_id', $id)
            ->getQuery()
            ->getResult();
    }

    public function getComprobantesPagoValidadosByReferenciaBancaria($referenciaBancaria)
    {
        return $this->createQueryBuilder('pago')
            ->where('pago.referenciaBancaria = :referenciaBancaria')
            ->andWhere('pago.validado IS NOT NULL')
            ->setParameter('referenciaBancaria', $referenciaBancaria)
            ->getQuery()
            ->getResult()
        ;
    }
}
