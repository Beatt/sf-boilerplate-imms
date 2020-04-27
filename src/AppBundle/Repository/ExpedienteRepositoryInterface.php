<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface ExpedienteRepositoryInterface extends ObjectRepository
{
    function getAllExpedientesByRequest($id);
}
