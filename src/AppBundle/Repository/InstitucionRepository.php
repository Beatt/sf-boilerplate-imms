<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

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
}
