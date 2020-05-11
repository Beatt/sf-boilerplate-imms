<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class InstitucionRepository extends EntityRepository implements InstitucionRepositoryInterface
{
    public function findAllPrivate()
    {
        return $this->createQueryBuilder('institucion')
            ->join('institucion.convenios', 'convenio')
            ->where('convenio.sector = :private')
            ->setParameter('private', 'Privado')
            ->getQuery()
            ->getResult();
    }

    function getInstitucionBySolicitudId($id)
    {
        try {
            return $this->createQueryBuilder('institucion')
                ->join('institucion.convenios', 'convenios')
                ->join('convenios.camposClinicos', 'camposClinicos')
                ->join('camposClinicos.solicitud', 'solicitud')
                ->where('solicitud.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }
}
