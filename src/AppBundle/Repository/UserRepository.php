<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        try {
            return $this->createQueryBuilder('u')
                ->where("(u.matricula is not null and concat(u.matricula, '') = :username) OR u.correo = :email")
                ->andWhere('u.activo = true')
                ->setParameter('username', $username)
                ->setParameter('email', $username)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (\Exception $e) {
        }
    }

    public function getCameByDelegacion($delegacion_id)
    {
        return $this->createQueryBuilder('usuario')
            ->innerJoin('usuario.delegaciones', 'delegaciones')
            ->innerJoin('usuario.permisos', 'permiso')
            ->where('delegaciones.id = :delegacion')
            ->andWhere('usuario.activo = true')
            ->andWhere('permiso.clave = :clave')
            ->setParameter('delegacion', $delegacion_id)
            ->setParameter('clave', 'CAME')
            ->orderBy('usuario.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

  public function getJDESByUnidad($unidad_id)
  {
      $qb = $this->createQueryBuilder('usuario')
        ->innerJoin('usuario.unidades', 'unidades')
        ->innerJoin('usuario.permisos', 'permiso')
        ->where('unidades.id = :unidad')
        ->andWhere('usuario.activo = true');
        $qb->andWhere(
          $qb->expr()->in('permiso.clave', ['CAME', 'JDES']) )
        ->setParameter('unidad', $unidad_id)
        ->orderBy('usuario.id', 'DESC')
        ->setMaxResults(1);

      return $qb->getQuery()
        ->getOneOrNullResult();
  }
}
