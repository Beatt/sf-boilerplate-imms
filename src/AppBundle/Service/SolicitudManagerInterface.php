<?php


namespace AppBundle\Service;


use AppBundle\Entity\Solicitud;
use Symfony\Component\HttpFoundation\Request;

interface SolicitudManagerInterface
{
    public function update(Solicitud $solicitud);

    public function create(Solicitud $solicitud);

    public function finalizar(Solicitud $solicitud);

    public function validarMontos(Solicitud $solicitud, $montos, $is_valid);
}