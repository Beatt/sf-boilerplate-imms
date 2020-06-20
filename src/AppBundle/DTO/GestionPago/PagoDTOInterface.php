<?php

namespace AppBundle\DTO\GestionPago;

interface PagoDTOInterface
{
    public function getComprobanteConEnlace();

    public function getReferenciaBancaria();

    public function getFechaPago();

    public function getMonto();
}
