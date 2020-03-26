<?php

namespace AppBundle\Repository\Admin;

use AppBundle\Repository\DepartmentRepository;
use AppBundle\Repository\PermissionRepository;
use AppBundle\Repository\RoleRepository;
use Doctrine\ORM\EntityRepository;

class AdminRepository extends EntityRepository
{
    public static function getAll(PermissionRepository $permissionRepository)
    {
        return $permissionRepository
            ->createQueryBuilder('permission')
            ->orderBy('permission.rolSeguridad', 'ASC');
    }

    public static function getAllRoles(RoleRepository $roleRepository)
    {
        return $roleRepository
            ->createQueryBuilder('role')
            ->orderBy('role.nombre', 'ASC');
    }

    public static function getAllDepartments(DepartmentRepository $departmentRepository)
    {
        return $departmentRepository
            ->createQueryBuilder('department')
            ->orderBy('department.nombre', 'ASC');
    }
}
