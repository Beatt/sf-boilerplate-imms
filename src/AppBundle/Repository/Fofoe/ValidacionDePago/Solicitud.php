<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

final class Solicitud
{
    private $noSolicitud;

    private $tipoPago;

    private $campoClinico;

    private $esUMAE;

    private $nombreUnidad;

    public function __construct(
      $noSolicitud,
      $tipoPago,
      CampoClinico $campoClinico,
      $esUMAE,
      $nombreUnidad
      )
    {
        $this->noSolicitud = $noSolicitud;
        $this->tipoPago = $tipoPago;
        $this->campoClinico = $campoClinico;
        $this->esUMAE = $esUMAE;
        $this->nombreUnidad = $nombreUnidad;
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

    public function getNombreUnidad()
    {
      return $this->nombreUnidad;
    }

    public function getEsUMAE() {
      return $this->esUMAE;
    }
}
