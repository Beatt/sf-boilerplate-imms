<?php

namespace AppBundle\DTO\IE\GestionPago;

interface PagoDTOInterface
{
    public function getId();

    public function getReferenciaBancaria();

    public function getFechaPago();

    public function getMonto();
}
