<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface SolicitudRepositoryInterface extends ObjectRepository
{
    const PAGINATOR_PER_PAGE = 2;

    function getAllSolicitudesByInstitucion($id, $offset, $search = null);
}
