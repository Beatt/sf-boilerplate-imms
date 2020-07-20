<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

final class Pago
{
    private $solicitud;

    private $montoTotal;

    public function __construct(
        Solicitud $solicitud,
        $montoTotal
    ) {
        $this->solicitud = $solicitud;
        $this->montoTotal = $montoTotal;
    }

    /**
     * @return Solicitud
     */
    public function getSolicitud()
    {
        return $this->solicitud;
    }

    /**
     * @return string
     */
    public function getMontoTotal()
    {
        return $this->montoTotal;
    }
}
