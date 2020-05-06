<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CarreraRepository extends EntityRepository
{
    function getAllCarrerasActivas() {
      return $this->findBy(array("activo" => true));
    }
}
