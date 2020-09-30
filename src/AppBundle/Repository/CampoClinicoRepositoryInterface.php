<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface CampoClinicoRepositoryInterface extends ObjectRepository
{
    function getAllCamposClinicosByInstitucion($id);

    function getAllCamposClinicosByRequest($id, $search = null, $autorizados);

    function getTotalSolicitudesByInstitucion($id);

    function getDistinctCarrerasBySolicitud($id);

    function getAllCamposByPage($filtros);

    function getReporteOportunidadPago($filtros);

    function getAutorizadosBySolicitud($id);
}
