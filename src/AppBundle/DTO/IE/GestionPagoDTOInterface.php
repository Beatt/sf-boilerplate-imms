<?php

namespace AppBundle\DTO\IE;

interface GestionPagoDTOInterface
{
    public function getNoSolicitud();

    public function getPagos();

    public function getUltimoPago();

    public function getMontoTotal();

    public function getMontoTotalPorPagar();

    public function getTipoPago();

    public function getCampoClinico();
}
