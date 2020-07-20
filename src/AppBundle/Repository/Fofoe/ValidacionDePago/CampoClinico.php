<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

final class CampoClinico
{
    private $sede;

    private $carrera;

    public function __construct($sede, $carrera)
    {
        $this->sede = $sede;
        $this->carrera = $carrera;
    }

    /**
     * @return string
     */
    public function getSede()
    {
        return $this->sede;
    }

    /**
     * @return string
     */
    public function getCarrera()
    {
        return $this->carrera;
    }
}
