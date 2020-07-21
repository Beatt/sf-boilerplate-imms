<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

final class Institucion
{
    private $nombre;

    private $delegacion;

    public function __construct($nombre, $delegacion)
    {
        $this->nombre = $nombre;
        $this->delegacion = $delegacion;
    }

    /**
     * @return string
     */
    public function getDelegacion()
    {
        return $this->delegacion;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }
}
