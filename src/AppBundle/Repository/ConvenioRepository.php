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
            ->setParameter('id', $id)
            ->orderBy('convenio.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $solicitud_id
     * @author Christian Garcia
     */
    public function getAllBySolicitud($solicitud_id){
        return $this->createQueryBuilder('convenio')
            ->join('convenio.camposClinicos', 'campo_clinico')
            ->join('campo_clinico.solicitud', 'solicitud')
            ->where('solicitud.id = :solicitud_id')
            ->setParameter('solicitud_id', $solicitud_id)
            ->getQuery()
            ->getResult();
    }

    public function getConveniosByDelegacion($delegacion_id = 1)
    {
        return  $this->createQueryBuilder('convenio')
            ->innerJoin('convenio.cicloAcademico', 'cicloAcademico')
            ->where('convenio.delegacion = :delegacion_id')
            ->andWhere('cicloAcademico.activo = true')
            ->setParameter('delegacion_id', $delegacion_id)
            ->getQuery()
            ->getResult();
    }
}
