<?php

namespace AppBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;

interface SolicitudRepositoryInterface extends ObjectRepository
{
    const FILTER_FOR_ORDERING_NO_SOLICITUD_MENOR_A_MAYOR = 'order_by_no_solicitud_menor_a_mayor';
    const FILTER_FOR_ORDERING_NO_SOLICITUD_MAYOR_A_MENOR = 'order_by_no_solicitud_mayor_a_menor';
    const FILTER_FOR_ORDERING_FECHA_DE_SOLICITUD_MAS_RECIENTE = 'order_by_fecha_de_solicitud_mas_reciente';
    const FILTER_FOR_ORDERING_FECHA_DE_SOLICITUD_MAS_ANTIGUA = 'order_by_fecha_de_solicitud_mas_antigua';

    function getSolicitudesByInstitucion($id);
    
    public function getAllSolicitudesByInstitucion($id, $tipoPago, $estatus, $orderBy, $search = null);

    public function getSolicitudesPagadas($perPage = 10, $offset = 1, $filters = []);
}
