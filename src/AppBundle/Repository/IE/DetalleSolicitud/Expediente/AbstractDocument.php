<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

abstract class AbstractDocument implements DocumentInterface
{
    private $nombre;

    private $fecha;

    private $descripcion;

    private $urlArchivo;

    private $options;

    public function __construct($nombre, $fecha, $descripcion, $urlArchivo, $options = null)
    {
        $this->nombre = $nombre;
        $this->fecha = $fecha;
        $this->descripcion = $descripcion;
        $this->urlArchivo = $urlArchivo;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return string
     */
    public function getFecha()
    {
        return (new \DateTime($this->fecha))->format('d/m/Y');
    }

    /**
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @return string
     */
    public function getUrlArchivo()
    {
        return $this->urlArchivo;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }
}
