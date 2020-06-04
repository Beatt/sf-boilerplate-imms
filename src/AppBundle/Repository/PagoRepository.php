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

       $qb = $this->createQueryBuilder('pago')
         ->select(array(
           "to_char(pago.fechaPago, 'YYYY') as Anio",
           "to_char(pago.fechaPago, 'MM') as Mes",
           "sum(case when convenio.cicloAcademico = 1 
                then campos_clinicos.monto else 0 end) as ingCCS",
           "sum(case when convenio.cicloAcademico = 2 
                then campos_clinicos.monto else 0 end) as ingINT",
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
       //$qb->andWhere('pago.validado');

       $qb->groupBy('Anio, Mes')
        ->orderBy('Anio', 'DESC')
       ->addOrderBy('Mes', 'ASC');

       return $qb->getQuery()->getResult();

     }
}
