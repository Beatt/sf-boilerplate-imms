<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface SolicitudRepositoryInterface extends ObjectRepository
{
    const PAGINATOR_PER_PAGE = 10;

    function getAllSolicitudesByInstitucion($id, $tipoPago, $offset, $search = null);
    
    function getSolicitudesByInstitucion($id);

    public function getSolicitudesPagadas($perPage = 10, $offset = 1, $filters = []);
}
