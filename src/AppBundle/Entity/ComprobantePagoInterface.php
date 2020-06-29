<?php

namespace AppBundle\Entity;

interface ComprobantePagoInterface
{
    public function getId();

    public function getReferenciaBancaria();

    public function getMonto();

    public function getFechaPago();
}
