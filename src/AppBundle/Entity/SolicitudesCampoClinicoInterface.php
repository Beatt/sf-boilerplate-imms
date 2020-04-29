<?php

namespace AppBundle\Entity;

interface SolicitudesCampoClinicoInterface
{
    public function getNoSolicitud();
    public function getFecha();
    public function getEstatusActual();
    public function getNoCamposSolicitados();
    public function getNoCamposAutorizados();
}
