<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface InstitucionRepositoryInterface extends ObjectRepository
{
    function getInstitucionBySolicitudId($id);

    public function searchOneByNombre($nombre);

    public function getInstitucionByPagoId($id);
}
