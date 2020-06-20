<?php

namespace AppBundle\DTO;

interface GestionPagoDTOInterface
{
    public function getNoSolicitud();

    public function getPagos();

    public function getUltimoPago();

    public function getMontoTotal();

    public function getMontoTotalPorPagar();
}
