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
                ->setParameter('username', $username)
                ->setParameter('email', $username)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (\Exception $e) {
        }
    }
}
