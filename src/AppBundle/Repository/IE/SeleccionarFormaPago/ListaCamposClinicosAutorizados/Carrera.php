<?php

namespace AppBundle\Repository\IE\SeleccionarFormaPago\ListaCamposClinicosAutorizados;

final class Carrera
{
    private  $id;
    private $nombre;
    private $nivelAcademico;

    public function __construct($id, $nombre, NivelAcademico $nivelAcademico)
    {
        $this->id = (int) $id;
        $this->nombre = $nombre;
        $this->nivelAcademico = $nivelAcademico;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
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
