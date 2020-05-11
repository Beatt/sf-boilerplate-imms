<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class InstitucionRepository extends EntityRepository implements InstitucionRepositoryInterface
{
    /**
     * @param int $delegacion_id
     * @return array|int|string
     * @author Christian Garcia
     */
    public function findAllPrivate($delegacion_id = null)
    {
        $querybuilder = $this->createQueryBuilder('institucion')
            ->innerJoin('institucion.convenios', 'convenio')
            ->innerJoin('convenio.carrera', 'carrera')
            ->innerJoin('convenio.cicloAcademico', 'ciclo')
            ->innerJoin('carrera.nivelAcademico', 'nivelAcademico')
            ->where('convenio.sector = :private')
            ->setParameter('private', 'Privado');
        if ($delegacion_id) {
            $querybuilder->andWhere('convenio.delegacion = :delegacion_id')
                ->setParameter('delegacion_id', $delegacion_id);
        }

        return $querybuilder->getQuery()
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
