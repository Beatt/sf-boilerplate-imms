<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos;

final class CicloAcademico
{
    private $nombre;

    public function __construct($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }
}
