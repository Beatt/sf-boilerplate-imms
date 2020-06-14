<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Pago;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

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

      return $qb->getQuery()->getResult();
    }
}
