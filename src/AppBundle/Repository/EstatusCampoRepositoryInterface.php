<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface EstatusCampoRepositoryInterface extends ObjectRepository
{
    function getEstatusPagado();
}
