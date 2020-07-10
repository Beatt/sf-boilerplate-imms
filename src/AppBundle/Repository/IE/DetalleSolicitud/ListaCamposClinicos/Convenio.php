<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos;

final class Convenio
{
    private $carrera;

    private $cicloAcademico;

    public function __construct(Carrera $carrera, CicloAcademico $cicloAcademico)
    {
        $this->carrera = $carrera;
        $this->cicloAcademico = $cicloAcademico;
    }

    /**
     * @return Carrera
     */
    public function getCarrera()
    {
        return $this->carrera;
    }

    /**
     * @return CicloAcademico
     */
    public function getCicloAcademico()
    {
        return $this->cicloAcademico;
    }
}
