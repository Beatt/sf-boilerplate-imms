<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface ConvenioRepositoryInterface extends ObjectRepository
{
    function getAllNivelesByConvenio($id);
}
