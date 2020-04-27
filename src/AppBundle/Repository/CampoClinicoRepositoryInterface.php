<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface CampoClinicoRepositoryInterface extends ObjectRepository
{
    function getAllCamposClinicosByInstitucion($id);

    function getAllCamposClinicosByRequest($id);
}
