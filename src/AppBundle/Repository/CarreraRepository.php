<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class CarreraRepository extends EntityRepository implements CarreraRepositoryInterface
{
    public function getDistinctCarrerasBySolicitud($id)
    {

        try {
            return $this->createQueryBuilder('carrera')
                ->select('carrera')
                ->join('carrera.id', 'convenio')
                ->join('convenio.id', 'campo_clinico')
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
    function getAllCarrerasActivas() {
      return $this->findBy(array("activo" => true));
    }

  public function searchOneByNombreGrado($nombre, $grado) {
    return $this->createQueryBuilder('c')
      ->join('c.nivelAcademico', 'nivel')
      ->where('LOWER(unaccent(c.nombre)) LIKE LOWER(unaccent(:nombre))')
      ->ANDwhere('LOWER(unaccent(nivel.nombre)) LIKE LOWER(unaccent(:grado))')
      ->setParameter('nombre', $nombre)
      ->setParameter('grado', $grado)
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
  }
}
