<?php

namespace AppBundle\DTO\GestionPago;

use AppBundle\Entity\Pago;

class PagoDTO implements PagoDTOInterface
{
    private $pago;

    public function __construct(Pago $pago)
    {
        $this->pago = $pago;
    }

    public function getComprobanteConEnlace()
    {
        return $this->pago->getComprobantePago();
    }

    public function getReferenciaBancaria()
    {
        return $this->pago->getReferenciaBancaria();
    }

    public function getFechaPago()
    {
        return $this->pago->getFechaPago();
    }

    public function getMonto()
    {
        return $this->pago->getMonto();
    }
}
