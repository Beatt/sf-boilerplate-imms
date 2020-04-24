<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ConvenioRepository extends EntityRepository implements ConvenioRepositoryInterface
{
    /**
     * @param $id
     * @return array
     */
    public function getAllNivelesByConvenio($id)
    {
        return $this->createQueryBuilder('convenio')
            ->join('convenio.carrera', 'carrera')
            ->join('carrera.nivelAcademico', 'nivel_academico')
            ->where('convenio.institucion = :id')
            ->orderBy('convenio.id', 'ASC')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}