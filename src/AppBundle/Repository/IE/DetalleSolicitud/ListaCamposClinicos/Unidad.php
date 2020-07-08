<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos;

final class Unidad
{
    private $nombre;

    public function __construct($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
}
