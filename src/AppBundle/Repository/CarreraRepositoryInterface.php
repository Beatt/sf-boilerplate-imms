<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface CarreraRepositoryInterface extends ObjectRepository
{
    public function getDistinctCarrerasBySolicitud($id);
}
