<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

final class PagoValidado
{
    private $id;

    private $referenciaBancaria;

    private $fechaPago;

    private $monto;

    public function __construct(
        $id,
        $referenciaBancaria,
        $fechaPago,
        $monto
    ) {
        $this->id = $id;
        $this->referenciaBancaria = $referenciaBancaria;
        $this->fechaPago = $fechaPago;
        $this->monto = $monto;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getReferenciaBancaria()
    {
        return $this->referenciaBancaria;
    }

    public function getFechaPago()
    {
        return $this->fechaPago->format('d-m-Y');
    }

    public function getMonto()
    {
        return $this->monto;
    }
}
