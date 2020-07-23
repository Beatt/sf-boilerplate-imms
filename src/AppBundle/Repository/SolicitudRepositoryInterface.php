<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface SolicitudRepositoryInterface extends ObjectRepository
{
    public function getAllSolicitudesByInstitucion($id, $perPage, $tipoPago, $offset, $search = null);

    public function getSolicitudesPagadas($perPage = 10, $offset = 1, $filters = []);
}
