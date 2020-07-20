<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

final class Pago
{
    private $solicitud;

    private $montoTotal;

    private $montoPendienteValidar;

    private $comprobantePago;

    private $fechaPago;

    private $monto;

    private $id;

    public function __construct(
        $id,
        Solicitud $solicitud,
        $montoTotal,
        $montoPendienteValidar,
        $comprobantePago,
        $fechaPago,
        $monto
    ) {
        $this->solicitud = $solicitud;
        $this->montoTotal = $montoTotal;
        $this->montoPendienteValidar = $montoPendienteValidar;
        $this->comprobantePago = $comprobantePago;
        $this->fechaPago = $fechaPago;
        $this->monto = $monto;
        $this->id = $id;
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

    /**
     * @return string
     */
    public function getComprobantePago()
    {
        return $this->comprobantePago;
    }

    /**
     * @return string
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }

    /**
     * @return string
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
