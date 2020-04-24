<?php


namespace AppBundle\Service;


use AppBundle\Entity\Solicitud;

interface SolicitudManagerInterface
{
    public function update(Solicitud $solicitud);

    public function create(Solicitud $solicitud);
}