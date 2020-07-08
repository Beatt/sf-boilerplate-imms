<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos;

final class Carrera
{
    private $nombre;

    private $nivelAcademico;

    public function __construct($nombre, NivelAcademico $nivelAcademico)
    {
        $this->nombre = $nombre;
        $this->nivelAcademico = $nivelAcademico;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return NivelAcademico
     */
    public function getNivelAcademico()
    {
        return $this->nivelAcademico;
    }
}
