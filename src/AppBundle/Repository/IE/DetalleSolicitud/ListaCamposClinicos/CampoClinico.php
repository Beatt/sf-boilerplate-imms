<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos;

final class CampoClinico
{
    private $id;

    private $convenio;

    private $lugaresSolicitados;

    private $lugaresAutorizados;

    private $fechaInicial;

    private $fechaFinal;

    private $unidad;

    public function __construct(
        $id,
        Convenio $convenio,
        $lugaresSolicitados,
        $lugaresAutorizados,
        $fechaInicial,
        $fechaFinal,
        Unidad $unidad
    ) {
        $this->id = $id;
        $this->convenio = $convenio;
        $this->lugaresSolicitados = $lugaresSolicitados;
        $this->lugaresAutorizados = $lugaresAutorizados;
        $this->fechaInicial = $fechaInicial;
        $this->fechaFinal = $fechaFinal;
        $this->unidad = $unidad;
    }

    /**
     * @return string
     */
    public function getLugaresSolicitados()
    {
        return $this->lugaresSolicitados;
    }

    /**
     * @return string
     */
    public function getLugaresAutorizados()
    {
        return $this->lugaresAutorizados;
    }

    /**
     * @return string
     */
    public function getFechaInicial()
    {
        return $this->fechaInicial;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Convenio
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * @return string
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }

    /**
     * @return Unidad
     */
    public function getUnidad()
    {
        return $this->unidad;
    }
}
