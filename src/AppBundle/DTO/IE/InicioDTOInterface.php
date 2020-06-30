<?php

namespace AppBundle\DTO\IE;

interface InicioDTOInterface
{
    public function getId();

    public function getEstatus();

    public function getFecha();

    public function getNoCamposAutorizados();

    public function getNoCamposSolicitados();

    public function getNoSolicitud();

    public function getTipoPago();

    public function getUltimoPago();
}
