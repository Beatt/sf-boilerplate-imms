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

    public function paginate($perPage = 10, $offset = 1, $filters = [])
    {
        $queryBuilder = $this->createQueryBuilder('pago')
            ;
        $qb2 = clone $queryBuilder;

        return ['data' => $queryBuilder->orderBy('pago.id', 'DESC')->setMaxResults($perPage)
            ->setFirstResult(($offset-1) * $perPage)->getQuery()
            ->getResult(),
            'total' => $qb2->select('COUNT(pago.id)')->getQuery()->getSingleScalarResult()
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
}
