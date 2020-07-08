<?php

namespace AppBundle\Repository\IE\DetalleSolicitud;

final class Solicitud
{
    private $id;

    private $estatus;

    private $totalCamposClinicosAutorizados;

    private $camposClinicos;

    private $noSolicitud;

    public function __construct(
        $id,
        $estatus,
        $noSolicitud,
        array $camposClinicos,
        $totalCamposClinicosAutorizados
    ) {
        $this->id = $id;
        $this->estatus = $estatus;
        $this->totalCamposClinicosAutorizados = $totalCamposClinicosAutorizados;
        $this->camposClinicos = $camposClinicos;
        $this->noSolicitud = $noSolicitud;
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
}
