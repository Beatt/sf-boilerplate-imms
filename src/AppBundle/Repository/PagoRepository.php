<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Pago;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
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

        if(isset($filters['estado']) && $filters['estado']){
            switch ($filters['estado']){
                case 'a':
                    $queryBuilder->andWhere('pago.validado is null');
                    break;
                case 'b':
                    $queryBuilder->andWhere('pago.validado = true');
                    break;
                case 'c':
                    $queryBuilder->andWhere('pago.validado = true AND pago.requiereFactura = true AND pago.factura is NULL');
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

        if(isset($filter['orderby']) && $filter['orderby']) {
            switch ($filter['orderby']){
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

        return ['data' => $queryBuilder->setMaxResults($perPage)
            ->setFirstResult(($offset-1) * $perPage)->getQuery()
            ->getResult(),
            'total' => $qb2->select('COUNT(distinct pago.id)')->getQuery()->getSingleScalarResult()
        ];
    }

    public function getYears(){
        $rsm = new ResultSetMapping();
        $queryBuilder = $this->createNativeNamedQuery("select extract(YEAR from fecha_pago) from pago group by 1", $rsm);
        return $queryBuilder->getResult();
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

    public function getReporteOportunidadPago($filtros) {
      $qb = $this->createQueryBuilder('pago')
        ->innerJoin('pago.solicitud', 'solicitud')
        ->innerJoin('solicitud.camposClinicos', 'campos_clinicos')
        ->innerJoin('campos_clinicos.convenio', 'convenio');

      $qb->where(

          $qb->expr()->orX(
            'pago.referenciaBancaria = solicitud.referenciaBancaria',
            'pago.referenciaBancaria = campos_clinicos.referenciaBancaria'
          )
        );
      $qb->andWhere('pago.validado = TRUE');

      if ( array_key_exists('desde', $filtros)  && $filtros['desde']) {
        $qb = $qb->andWhere('pago.fechaPago >= :desde')
          ->setParameter('desde', new \DateTime($filtros['desde']));
      }

      if ( array_key_exists('hasta', $filtros)  && $filtros['hasta']) {
        $qb = $qb->andWhere('pago.fechaPago <= :hasta')
          ->setParameter('hasta', new \DateTime($filtros['hasta']));
      }

      if ( array_key_exists('search', $filtros) && $filtros['search']) {
        $qb = $qb->join('convenio.institucion', 'institucion');
        $qb = $qb
          ->andWhere(
            $qb->expr()->orX()->addMultiple(array(
              "UNACCENT(LOWER(institucion.nombre)) LIKE UNACCENT(LOWER(:search))",
              "LOWER(solicitud.referenciaBancaria) LIKE LOWER(:search)",
                ))
            )
          ->setParameter('search', '%' . $filtros['search'] . '%');
      }

      $qb = $qb->getQuery();

      // load doctrine Paginator
      $paginator = new Paginator($qb);

      // get total items
      $totalItems = count($paginator);

      $pageSize = array_key_exists('limit', $filtros)
      && $filtros['limit'] > 0 ?
        $filtros['limit'] : 10;
      $page = array_key_exists('limit', $filtros)
      && $filtros['page'] > 0 ? $filtros['page'] : 1;

      // get total pages
      $pagesCount = ceil($totalItems / $pageSize);

      $pagos = [];
      if(array_key_exists('export', $filtros) && $filtros['export']) {
        $pagos = $paginator
          ->getQuery()
          ->getResult();
      } else {
        $offset = $pageSize * ($page-1);
        // now get one page's items:
        $pagos = $paginator
          ->getQuery()
          ->setFirstResult($offset) // set the offset
          ->setMaxResults($pageSize) // set the limit}
          ->getResult();
      }

      return [$pagos, $totalItems, $pagesCount, $pageSize];

    }
}
