<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

final class Solicitud
{
    private $noSolicitud;

    private $tipoPago;

    private $campoClinico;

    public function __construct($noSolicitud, $tipoPago, CampoClinico $campoClinico)
    {
        $this->noSolicitud = $noSolicitud;
        $this->tipoPago = $tipoPago;
        $this->campoClinico = $campoClinico;
    }

    /**
     * @return string
     */
    public function getNoSolicitud()
    {
        return $this->noSolicitud;
    }

    /**
     * @return string
     */
    public function getTipoPago()
    {
        return $this->tipoPago;
    }

    /**
     * @return CampoClinico
     */
    public function getCampoClinico()
    {
        return $this->campoClinico;
    }
}
