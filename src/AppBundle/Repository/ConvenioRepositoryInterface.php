<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface ConvenioRepositoryInterface extends ObjectRepository
{
    public function getAllNivelesByConvenio($id);

    public function getConvenioGeneral($institucion_id, $vigencia );

    public function getConveniosUnicosByInstitucionId($id);
}
