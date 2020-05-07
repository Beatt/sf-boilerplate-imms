<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    function getAllCamposByPage($filtros) {
      $query =  $this->createQueryBuilder('campo_clinico')
        ->join('campo_clinico.convenio', 'convenio')
        ->join('campo_clinico.solicitud', 'solicitud')
        ->join('convenio.institucion', 'institucion')
        ->orderBy('institucion.nombre', 'ASC');

      if (@$filtros['search']) {
        $query = $query
          ->andWhere("LOWER(solicitud.noSolicitud) LIKE LOWER(:search)")
          //->orWhere("date_format(solicitud.fecha, 'dd/mm/YYYY') LIKE :search")
          ->orWhere("LOWER(institucion.nombre) LIKE LOWER(:search)")
          ->setParameter('search', '%' . $filtros['search'] . '%');
      }

      if (@$filtros['estatus']) {
        $query = $query->andWhere('campo_clinico.estatus = :status')
          ->setParameter('status', $filtros['estatus']);
      }

      if (@$filtros['cicloAcademico']) {
        $query = $query->andWhere('carrera.nivelAcademico = :ciclo')
          ->setParameter('ciclo', $filtros['cicloAcademico']);
      }

      if (@$filtros['carrera']) {
        $query = $query->andWhere('convenio.carrera = :carrera')
          ->setParameter('carrera', $filtros['carrera']);
      }

      if (@$filtros['delegacion']) {
        $query = $query->andWhere('convenio.delegacion = :delegacion')
          ->setParameter('delegacion', $filtros['delegacion']);
      }

      $query = $query->getQuery();

      // load doctrine Paginator
      $paginator = new Paginator($query);

      // get total items
      $totalItems = count($paginator);

      $pageSize = @$filtros['limit'] && $filtros['limit'] > 0 ?
        $filtros['limit'] : 10;
      $page = @$filtros['page']  && $filtros['page'] > 0 ? $filtros['page'] : 1;

      // get total pages
      $pagesCount = ceil($totalItems / $pageSize);

      $campos = [];
      if(@$filtros['export']) {
        $campos = $paginator
          ->getQuery()
          ->getResult();
      } else {
        // now get one page's items:
        $campos = $paginator
          ->getQuery()
          ->setFirstResult($pageSize * ($page-1)) // set the offset
          ->setMaxResults($pageSize) // set the limit}
          ->getResult();
      }

      return [$campos, $totalItems, $pagesCount];

    }

}
