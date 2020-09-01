<?php


namespace AppBundle\Service;


use AppBundle\Entity\Solicitud;
use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\Request;

interface SolicitudManagerInterface
{
    public function update(Solicitud $solicitud);

    public function create(Solicitud $solicitud);

    public function finalizar(Solicitud $solicitud, Usuario $came_usuario = null);

    public function registrarMontos(Solicitud $solicitud);

    public function validarMontos(Solicitud $solicitud, $montos, $is_valid, Usuario $came_usuario = null);

    public function generateUser(Solicitud $solicitud, Usuario $came_usuario = null);
}