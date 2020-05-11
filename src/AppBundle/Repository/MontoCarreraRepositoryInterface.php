<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\MontoCarrera;

interface MontoCarreraRepositoryInterface extends ObjectRepository
{
    function getAllMontosCarreraByRequest($id);
}
