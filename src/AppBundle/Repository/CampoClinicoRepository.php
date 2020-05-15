<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class CampoClinicoRepository extends EntityRepository implements CampoClinicoRepositoryInterface
{

    /**
     * @param $id
     * @return array
     */
    public function getAllCamposClinicosByInstitucion($id)
    {
        return $this->createQueryBuilder('campo_clinico')
            ->join('campo_clinico.convenio', 'convenio')
            ->join('convenio.institucion', 'institucion')
            ->where('institucion.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $id
     * @return array
     */
    public function getAllCamposClinicosByRequest($id, $search = null, $autorizados)
    {
        try{
            $queryBuilder = $this->createQueryBuilder('campo_clinico')
            ->where('campo_clinico.solicitud = :id')
            ->setParameter('id', $id);
            
            if($search !== null && $search !== '') {
                $queryBuilder = $queryBuilder
                    ->andWhere("LOWER(campo_clinico.promocion) LIKE LOWER(:search)")
                    ->setParameter('search', '%' . $search . '%')
                ;
            }

            if($autorizados) {
                $queryBuilder = $queryBuilder
                    ->andWhere("campo_clinico.lugaresAutorizados <> 0")
                ;
            }

            return $queryBuilder
                ->getQuery()
                ->getResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

        return 0;

    }

    public function getTotalSolicitudesByInstitucion($id)
    {
        try {
            return $this->createQueryBuilder('campo_clinico')
                ->select('count(campo_clinico.id)')
                ->join('campo_clinico.convenio', 'convenio')
                ->join('convenio.institucion', 'institucion')
                ->join('campo_clinico.solicitud', 'solicitud')
                ->where('institucion.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

        return 0;
    }

    public function getDistinctCarrerasBySolicitud($id)
    {
        try {
            return $this->createQueryBuilder('campo_clinico')
                ->select('carrera.id, carrera.nombre, carrera.activo, nivel_academico.nombre as nivel')
                ->join('campo_clinico.convenio', 'convenio')
                ->join('convenio.carrera', 'carrera')
                ->join('carrera.nivelAcademico', 'nivel_academico')
                ->where('campo_clinico.solicitud = :id')
                ->setParameter('id', $id)
                ->distinct()
                ->getQuery()
                ->getResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

        return 0;
    }


    public function getAllCamposClinicosBySolicitud($solicitud_id, $perPage = 10, $offset = 1, $filters = [])
    {
        $queryBuilder = $this->createQueryBuilder('campoClinico')
            ->innerJoin('campoClinico.solicitud', 'solicitud')
            ->innerJoin('campoClinico.unidad', 'unidad')
            ->innerJoin('campoClinico.convenio', 'convenio')
            ->innerJoin('convenio.carrera', 'carrera')
            ->innerJoin('carrera.nivelAcademico', 'nivelAcademico')
            ->innerJoin('convenio.cicloAcademico',  'cicloAcademico')
            ->where('solicitud.id = :solicitud_id')
            ->setParameter('solicitud_id', $solicitud_id);

        if(isset($filters['unidad']) && $filters['unidad']){
            $queryBuilder->andWhere('upper(unidad.nombre) like :unidad')
                ->setParameter('unidad', '%'.strtoupper($filters['unidad']).'%');
        }

        if(isset($filters['carrera']) && $filters['carrera']){
            $queryBuilder->andWhere('upper(unidad.nombre) like :unidad')
                ->setParameter('unidad', '%'.strtoupper($filters['unidad']).'%');
        }

        if(isset($filters['cicloAcademico']) && $filters['cicloAcademico']){
            $queryBuilder->andWhere('upper(cicloAcademico.nombre) like :cicloAcademico')
                ->setParameter('cicloAcademico', '%'.strtoupper($filters['cicloAcademico']).'%');
        }

        if(isset($filters['nivelAcademico']) && $filters['nivelAcademico']){
            $queryBuilder->andWhere('upper(nivelAcademico.nombre) like :nivelAcademico')
                ->setParameter('nivelAcademico', '%'.strtoupper($filters['nivelAcademico']).'%');
        }


        $queryBuilder->setMaxResults($perPage)
            ->setFirstResult(($offset-1) * $perPage);

        return $queryBuilder->getQuery()->getResult();
    }
}
