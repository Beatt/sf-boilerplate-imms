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

    private $historial;

    private $institucion;

    public function __construct(
        $id,
        Solicitud $solicitud,
        $montoTotal,
        $montoPendienteValidar,
        $comprobantePago,
        $fechaPago,
        $monto,
        $historial,
        Institucion $institucion
    ) {
        $this->solicitud = $solicitud;
        $this->montoTotal = $montoTotal;
        $this->montoPendienteValidar = $montoPendienteValidar;
        $this->comprobantePago = $comprobantePago;
        $this->fechaPago = $fechaPago;
        $this->monto = $monto;
        $this->id = $id;
        $this->historial = $historial;
        $this->institucion = $institucion;
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

    /**
     * @return array
     */
    public function getHistorial()
    {
        return $this->historial;
    }

    /**
     * @return Institucion
     */
    public function getInstitucion()
    {
        return $this->institucion;
    }
}
