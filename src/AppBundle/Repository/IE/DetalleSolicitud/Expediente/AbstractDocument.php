<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

abstract class AbstractDocument implements DocumentInterface
{
    private $nombre;

    private $fecha;

    private $descripcion;

    private $urlArchivo;

    public function __construct($nombre, $fecha, $descripcion, $urlArchivo)
    {
        $this->nombre = $nombre;
        $this->fecha = $fecha;
        $this->descripcion = $descripcion;
        $this->urlArchivo = $urlArchivo;
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
        return (new \DateTime($this->fecha))->format('d-m-Y');
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
}
