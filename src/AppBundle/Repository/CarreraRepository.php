<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CarreraRepository extends EntityRepository implements CarreraRepositoryInterface
{
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
