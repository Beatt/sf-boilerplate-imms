<?php

namespace AppBundle\Repository\IE\DetalleSolicitud;

use AppBundle\Repository\IE\DetalleSolicitud\Expediente\Documents;

final class Solicitud
{
    private $id;

    private $estatus;

    private $totalCamposClinicosAutorizados;

    private $camposClinicos;

    private $noSolicitud;

    private $expediente;

    private $ultimoPago;

    public function __construct(
        $id,
        $estatus,
        $noSolicitud,
        array $camposClinicos,
        $totalCamposClinicosAutorizados,
        Documents $expediente,
        UltimoPago $ultimoPago
    ) {
        $this->id = $id;
        $this->estatus = $estatus;
        $this->totalCamposClinicosAutorizados = $totalCamposClinicosAutorizados;
        $this->camposClinicos = $camposClinicos;
        $this->noSolicitud = $noSolicitud;
        $this->expediente = $expediente;
        $this->ultimoPago = $ultimoPago;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * @return string
     */
    public function getTotalCamposClinicosAutorizados()
    {
        return $this->totalCamposClinicosAutorizados;
    }

    /**
     * @return array
     */
    public function getCamposClinicos()
    {
        return $this->camposClinicos;
    }

    /**
     * @return mixed
     */
    public function getNoSolicitud()
    {
        return $this->noSolicitud;
    }

    /**
     * @return Documents
     */
    public function getExpediente()
    {
        return $this->expediente;
    }

    /**
     * @return UltimoPago
     */
    public function getUltimoPago()
    {
        return $this->ultimoPago;
    }
}
