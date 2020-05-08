<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Pago;
use Doctrine\ORM\EntityRepository;

class PagoRepository extends EntityRepository implements PagoRepositoryInterface
{
    public function getComprobante($referenciaBancaria)
    {
        return $this->findOneByReferenciaBancaria($referenciaBancaria);
    }

    public function save(Pago $pago)
    {
        $this->_em->persist($pago);
        $this->_em->flush();
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
}
