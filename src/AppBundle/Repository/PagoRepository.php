<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Pago;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
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
            ;
        $qb2 = clone $queryBuilder;

        return ['data' => $queryBuilder->orderBy('pago.id', 'DESC')->setMaxResults($perPage)
            ->setFirstResult(($offset-1) * $perPage)->getQuery()
            ->getResult(),
            'total' => $qb2->select('COUNT(distinct pago.id)')->getQuery()->getSingleScalarResult()
        ];
    }

    public function getComprobantesPagoByReferenciaBancaria($referenciaBancaria)
    {
        return $this->createQueryBuilder('pago')
            ->select('NEW AppBundle\DTO\GestionPago\PagoDTO(
                pago.comprobantePago, 
                pago.referenciaBancaria, 
                pago.fechaPago, 
                pago.monto
            )')
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
