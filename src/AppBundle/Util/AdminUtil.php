<?php

namespace AppBundle\Util;

use AppBundle\Entity\Permiso;

class AdminUtil
{
    public static function getPermissionName(Permiso $permiso)
    {
        return "{$permiso->getClave()} ({$permiso->getNombre()})";
    }
}
