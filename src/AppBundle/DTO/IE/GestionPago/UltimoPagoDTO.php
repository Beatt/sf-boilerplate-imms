<?php

namespace AppBundle\DTO\IE\GestionPago;

use AppBundle\Entity\Pago;

final class UltimoPagoDTO implements UltimoPagoDTOInterface
{
    private $pago;

    public function __construct(Pago $pago)
    {
        $this->pago = $pago;
    }

    public function getObservaciones()
    {
        return $this->pago->getObservaciones();
    }
}
