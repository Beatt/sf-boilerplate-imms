<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

final class Pago
{
    private $solicitud;

    private $montoTotal;

    private $montoPendienteValidar;

    public function __construct(
        Solicitud $solicitud,
        $montoTotal,
        $montoPendienteValidar
    ) {
        $this->solicitud = $solicitud;
        $this->montoTotal = $montoTotal;
        $this->montoPendienteValidar = $montoPendienteValidar;
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

    /**
     * @return string
     */
    public function getMontoPendienteValidar()
    {
        return $this->montoPendienteValidar;
    }
}
