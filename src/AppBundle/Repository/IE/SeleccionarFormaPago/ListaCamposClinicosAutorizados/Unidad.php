<?php

namespace AppBundle\Repository\IE\SeleccionarFormaPago\ListaCamposClinicosAutorizados;

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
