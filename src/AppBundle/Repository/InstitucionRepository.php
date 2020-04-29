<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

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
}
