<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface CampoClinicoRepositoryInterface extends ObjectRepository
{
    const PAGINATOR_PER_PAGE = 1;

    function getAllCamposClinicosByInstitucion($id);

    function getAllCamposClinicosByRequest($id);

    function getAllSolicitudesByInstitucion($id, $offset, $search = null);

    function getTotalSolicitudesByInstitucion($id);
}
