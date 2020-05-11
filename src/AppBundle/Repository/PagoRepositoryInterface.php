<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface PagoRepositoryInterface extends ObjectRepository
{
    function getAllPagosByRequest($id);
}
