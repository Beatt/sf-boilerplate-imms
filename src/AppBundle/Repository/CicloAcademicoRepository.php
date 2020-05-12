<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CicloAcademicoRepository extends EntityRepository
{

  public function searchOneByNombre($nombre) {
    return $this->createQueryBuilder('ca')
      ->where('LOWER(unaccent(ca.nombre)) LIKE LOWER(unaccent(:nombre))')
      ->setParameter('nombre', $nombre)
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
  }

}
