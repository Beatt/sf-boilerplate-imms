<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface InstitucionRepositoryInterface extends ObjectRepository
{
  public function searchOneByNombre($nombre);
}
