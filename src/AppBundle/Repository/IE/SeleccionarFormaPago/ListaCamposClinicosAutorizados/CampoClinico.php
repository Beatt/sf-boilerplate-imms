<?php

namespace AppBundle\Repository\IE\SeleccionarFormaPago\ListaCamposClinicosAutorizados;

final class CampoClinico
{
    private $id;

    private $unidad;

    private $convenio;

    private $lugaresSolicitados;

    private $lugaresAutorizados;

    private $fechaInicial;

    private $numeroSemanas;

    private $montoPagar;

    private $enlaceCalculoCuotas;

    private $fechaFinal;

    public function __construct(
        $id,
        Unidad $unidad,
        Convenio $convenio,
        $lugaresSolicitados,
        $lugaresAutorizados,
        $fechaInicial,
        $fechaFinal,
        $numeroSemanas,
        $montoPagar,
        $enlaceCalculoCuotas
    ) {
        $this->id = $id;
        $this->unidad = $unidad;
        $this->convenio = $convenio;
        $this->lugaresSolicitados = $lugaresSolicitados;
        $this->lugaresAutorizados = $lugaresAutorizados;
        $this->fechaInicial = $fechaInicial;
        $this->numeroSemanas = $numeroSemanas;
        $this->montoPagar = $montoPagar;
        $this->enlaceCalculoCuotas = $enlaceCalculoCuotas;
        $this->fechaFinal = $fechaFinal;
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
     * @throws \Exception
     */
    public function getFechaInicial()
    {
        return (new \DateTime($this->fechaInicial))->format('d/m/Y');
    }

    /**
     * @return string
     */
    public function getNumeroSemanas()
    {
        return $this->numeroSemanas;
    }

    /**
     * @return string
     */
    public function getMontoPagar()
    {
        return $this->montoPagar;
    }

    /**
     * @return string
     */
    public function getEnlaceCalculoCuotas()
    {
        return $this->enlaceCalculoCuotas;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Unidad
     */
    public function getUnidad()
    {
        return $this->unidad;
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
     * @throws \Exception
     */
    public function getFechaFinal()
    {
        return (new \DateTime($this->fechaFinal))->format('d/m/Y');
    }
}
