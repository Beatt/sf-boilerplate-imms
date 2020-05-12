<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class InstitucionRepository extends EntityRepository implements InstitucionRepositoryInterface
{
    public function findAllPrivate()
    {
        return $this->createQueryBuilder('institucion')
            ->join('institucion.convenios', 'convenio')
            ->where('convenio.sector = :private')
            ->setParameter('private', 'Privado')
            ->getQuery()
            ->getResult();
    }

    public function searchOneByNombre($nombre) {
      return $this->createQueryBuilder('institucion')
        ->where('LOWER(unaccent(convenio.nombre)) LIKE LOWER(unaccent(:nombre))')
        ->setParameter('nombre', $nombre)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();
    }
}
