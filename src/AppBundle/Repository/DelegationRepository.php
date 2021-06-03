<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class DelegationRepository extends EntityRepository
{
    public function getAllDelegacionesNotNullRegion() {
        return $this->createQueryBuilder('d')
            ->where('d.region IS NOT NULL')
            ->andWhere('d.activo = TRUE')
            ->getQuery()
            ->getResult();
    }

  public function searchOneByNombre($nombre) {
    return $this->createQueryBuilder('d')
      ->where('LOWER(unaccent(d.nombre)) LIKE LOWER(unaccent(:nombre))')
      ->setParameter('nombre', $nombre)
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
  }
}
