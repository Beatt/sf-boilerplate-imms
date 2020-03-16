<?php

namespace AppBundle\Repository\Admin;

use AppBundle\Repository\PermissionRepository;
use Doctrine\ORM\EntityRepository;

class AdminRepository extends EntityRepository
{
    public static function getAll(PermissionRepository $permissionRepository)
    {
        return $permissionRepository
            ->createQueryBuilder('permission')
            ->orderBy('permission.name', 'ASC');
    }
}
