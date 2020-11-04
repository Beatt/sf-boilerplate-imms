<?php

namespace AppBundle\Repository\IE\SeleccionarFormaPago\ListaCamposClinicosAutorizados;

final class CicloAcademico
{
    private $id;
    private $nombre;

    public function __construct($id, $nombre)
    {
        $this->id = (int) $id;
        $this->nombre = $nombre;
    }

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
}
