<?php

namespace AppBundle\DTO\IE\GestionPago;

use AppBundle\Entity\Pago;

class PagoDTO implements PagoDTOInterface
{
    private $pago;

    public function __construct(Pago $pago)
    {
        $this->pago = $pago;
    }

    public function getReferenciaBancaria()
    {
        return $this->pago->getReferenciaBancaria();
    }

    public function getFechaPago()
    {
        return $this->pago->getFechaPago()->format('d-m-Y');
    }

    public function getMonto()
    {
        return $this->pago->getMonto();
    }

    public function getId()
    {
        return $this->pago->getId();
    }

    public function getObservaciones()
    {
        return $this->pago->getObservaciones();
    }
}
