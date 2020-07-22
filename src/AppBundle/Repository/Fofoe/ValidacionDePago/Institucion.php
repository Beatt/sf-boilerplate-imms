<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

final class Institucion
{
    private $nombre;

    private $delegacion;

    private $id;

    public function __construct($id, $nombre, $delegacion)
    {
        $this->nombre = $nombre;
        $this->delegacion = $delegacion;
        $this->id = $id;
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

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
