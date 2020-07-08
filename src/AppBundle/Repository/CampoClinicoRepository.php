<?php

namespace AppBundle\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
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
                         WHERE campo_clinico.lugares_autorizados <> 0 AND campo_clinico.solicitud_id = :id
                     ) as carreras_unicas
                         LEFT JOIN monto_carrera on carreras_unicas.id = monto_carrera.carrera_id
                         AND monto_carrera.solicitud_id = :id
            ');

            $stmt->bindParam('id', $id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (DBALException $e) {
        }

        return 0;
    }

    public function getAllCamposByPage($filtros) {
      $query =  $this->createQueryBuilder('campo_clinico')
        ->join('campo_clinico.convenio', 'convenio')
        ->join('campo_clinico.solicitud', 'solicitud')
        ->join('convenio.institucion', 'institucion')
        ->orderBy('institucion.nombre', 'ASC');

      $query = $this->procesarFiltros($query, $filtros);

      $query = $query->getQuery();

      list($paginator, $page, $pageSize, $pagesCount, $totalItems)
        = $this->setPaginador($query, $filtros);

      $campos = [];
      if(array_key_exists('export', $filtros) && $filtros['export']) {
        $campos = $paginator
          ->getQuery()
          ->getResult();
      } else {
        $offset = $pageSize * ($page-1);
        // now get one page's items:
        $campos = $paginator
          ->getQuery()
          ->setFirstResult($offset) // set the offset
          ->setMaxResults($pageSize) // set the limit}
          ->getResult();
      }

      return [$campos, $totalItems, $pagesCount, $pageSize];
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
            $queryBuilder->andWhere('upper(unaccent(unidad.nombre)) like UPPER(unaccent(:unidad))')
                ->setParameter('unidad', '%'.($filters['unidad']).'%');
        }

        if(isset($filters['carrera']) && $filters['carrera']){
            $queryBuilder->andWhere('upper(unaccent(carrera.nombre)) like UPPER(unaccent(:carrera))')
                ->setParameter('carrera', '%'.($filters['carrera']).'%');
        }

        if(isset($filters['cicloAcademico']) && $filters['cicloAcademico']){
            $queryBuilder->andWhere('upper(unaccent(cicloAcademico.nombre)) like UPPER(unaccent(:cicloAcademico))')
                ->setParameter('cicloAcademico', '%'.($filters['cicloAcademico']).'%');
        }

        if(isset($filters['nivelAcademico']) && $filters['nivelAcademico']){
            $queryBuilder->andWhere('upper(unaccent(nivelAcademico.nombre)) like UPPER(unaccent(:nivelAcademico))')
                ->setParameter('nivelAcademico', '%'.($filters['nivelAcademico']).'%');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function getAutorizadosBySolicitud($id)
    {
        try {
            $stmt = $this->_em->getConnection()->prepare('
                SELECT COUNT(*) as Autorizados
                FROM campo_clinico
                WHERE (lugares_autorizados <> 0 AND lugares_autorizados IS NOT NULL)
                AND solicitud_id = :id
            ');

            $stmt->bindParam('id', $id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (DBALException $e) {
        }

        return 0;
    }

    public static function getCampoClinicoByReferenciaBancaria($referenciaBancaria)
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('referenciaBancaria', $referenciaBancaria));
    }

  /**
   * @param array $filtros
   * @param string $param
   * @return boolean
   */
  private function verificarParametro($filtros, $param) {
    return array_key_exists($param, $filtros)  && $filtros[$param];
  }

  /**
   * @param QueryBuilder $query
   * @param array $filtros
   * @return QueryBuilder
   */
  private function procesarFiltrosCampo($query, $filtros) {
    if ( $this->verificarParametro($filtros, 'estatus')) {
      $query = $query->andWhere('campo_clinico.estatus = :status')
        ->setParameter('status', $filtros['estatus']);
    }

    if ( $this->verificarParametro($filtros, 'fechaIni') ) {
      $query = $query->andWhere('campo_clinico.fechaInicial >= :fechaIni')
        ->setParameter('fechaIni', new \DateTime($filtros['fechaIni']) );
    }

    if ( $this->verificarParametro($filtros, 'fechaFin') ) {
      $query = $query->andWhere('campo_clinico.fechaFinal <= :fechaFin')
        ->setParameter('fechaFin', new \DateTime($filtros['fechaFin']));
    }

    if ( $this->verificarParametro($filtros, 'unidad') ) {
      $query = $query->andWhere('campo_clinico.unidad = :unidad')
        ->setParameter('unidad', $filtros['unidad']);
    }
    return $query;
  }

  /**
   * @param QueryBuilder $query
   * @param array $filtros
   * @return QueryBuilder
   */
  private function procesarFiltrosConvenio($query, $filtros) {
    if ( $this->verificarParametro($filtros, 'carrera') ) {
      $query = $query->andWhere('convenio.carrera = :carrera')
        ->setParameter('carrera', $filtros['carrera']);
    }

    if ( $this->verificarParametro($filtros, 'delegacion') ) {
      $query = $query->andWhere('convenio.delegacion = :delegacion')
        ->setParameter('delegacion', $filtros['delegacion']);
    }
    return $query;
  }

  /**
   * @param QueryBuilder $query
   * @param array $filtros
   * @return QueryBuilder
   */
  private function procesarFiltros($query, $filtros) {
    if ( $this->verificarParametro($filtros, 'search') ) {
      $query = $query
        ->andWhere("LOWER(solicitud.noSolicitud) LIKE LOWER(:search)")
        //->orWhere("date_format(solicitud.fecha, 'dd/mm/YYYY') LIKE :search")
        ->orWhere("LOWER(institucion.nombre) LIKE LOWER(:search)")
        ->setParameter('search', '%' . $filtros['search'] . '%');
    }

    $query = $this->procesarFiltrosCampo($query, $filtros);

    if ( $this->verificarParametro($filtros, 'cicloAcademico' ) ) {
      $query = $query->andWhere('carrera.nivelAcademico = :ciclo')
        ->setParameter('ciclo', $filtros['cicloAcademico']);
    }

    $query = $this->procesarFiltrosConvenio($query, $filtros);

    return $query;
  }

  /**
   * @param QueryBuilder $query
   * @param array $filtros
   * @return array
   */
  private function setPaginador($query, $filtros) {
    // load doctrine Paginator
    $paginator = new Paginator($query);

    // get total items
    $totalItems = count($paginator);

    $pageSize = array_key_exists('limit', $filtros)
    && $filtros['limit'] > 0 ?
      $filtros['limit'] : 10;
    $page = array_key_exists('limit', $filtros)
    && $filtros['page'] > 0 ? $filtros['page'] : 1;

    // get total pages
    $pagesCount = ceil($totalItems / $pageSize);

    return array($paginator, $page, $pageSize, $pagesCount, $totalItems);
  }
}
