<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos;

use DateTime;

class CampoClinico
{
    private $id;

    private $convenio;

    private $lugaresSolicitados;

    private $lugaresAutorizados;

    private $fechaInicial;

    private $fechaFinal;

    private $unidad;

    private $noSemanas;

    public function __construct(
        $id,
        Convenio $convenio,
        $lugaresSolicitados,
        $lugaresAutorizados,
        $fechaInicial,
        $fechaFinal,
        Unidad $unidad,
        $noSemanas
    ) {
        $this->id = $id;
        $this->convenio = $convenio;
        $this->lugaresSolicitados = $lugaresSolicitados;
        $this->lugaresAutorizados = $lugaresAutorizados;
        $this->fechaInicial = $fechaInicial;
        $this->fechaFinal = $fechaFinal;
        $this->unidad = $unidad;
        $this->noSemanas = $noSemanas;
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
        return (new DateTime($this->fechaInicial))->format('d-m-Y');
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
        return (new DateTime($this->fechaFinal))->format('d-m-Y');
    }

    /**
     * @return Unidad
     */
    public function getUnidad()
    {
        return $this->unidad;
    }

    /**
     * @return string
     */
    public function getNoSemanas()
    {
        return $this->noSemanas;
    }
}
