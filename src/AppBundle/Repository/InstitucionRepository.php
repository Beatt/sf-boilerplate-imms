<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class InstitucionRepository extends EntityRepository implements InstitucionRepositoryInterface
{
    public function findAllPrivate()
    {
        return $this->createQueryBuilder('institucion')
            ->innerJoin('institucion.convenios', 'convenio')
            ->innerJoin('convenio.carrera', 'carrera')
            ->innerJoin('convenio.cicloAcademico', 'ciclo')
            ->innerJoin('carrera.nivelAcademico', 'nivelAcademico')
            ->where('convenio.sector = :private')
            ->setParameter('private', 'Privado')
            ->getQuery()
            ->getResult();
    }
}
