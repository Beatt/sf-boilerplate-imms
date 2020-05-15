<?php

namespace AppBundle\Repository;

use Doctrine\DBAL\DBALException;
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
            $stmt = $this->_em->getConnection()->prepare('
                SELECT carreras_unicas.*,
                       monto_carrera.monto_colegiatura,
                       monto_carrera.monto_inscripcion
                FROM (
                         SELECT DISTINCT carrera.id             AS id,
                                         carrera.nombre         AS nombre,
                                         nivel_academico.nombre as nivel_academico
                         FROM campo_clinico
                                  JOIN convenio on campo_clinico.convenio_id = convenio.id
                                  JOIN carrera on convenio.carrera_id = carrera.id
                                  JOIN nivel_academico on carrera.nivel_academico_id = nivel_academico.id
                                  JOIN solicitud on campo_clinico.solicitud_id = solicitud.id
                         WHERE campo_clinico.solicitud_id = :id
                     ) as carreras_unicas
                         LEFT JOIN monto_carrera on carreras_unicas.id = monto_carrera.carrera_id
            ');

            $stmt->bindParam('id', $id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (DBALException $e) {
        }

        return 0;
    }
}
