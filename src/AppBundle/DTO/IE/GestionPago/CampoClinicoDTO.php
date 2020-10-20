<?php

namespace AppBundle\DTO\IE\GestionPago;

use AppBundle\Entity\CampoClinico;

final class CampoClinicoDTO
{
    private $campoClinico;

    public function __construct(CampoClinico $campoClinico)
    {
        $this->campoClinico = $campoClinico;
    }

    public function getSede()
    {
        $unidad = $this->campoClinico
            ->getUnidad();

        return $unidad !== null ?
            $this->campoClinico
                ->getUnidad()
                ->getNombre() :
            'Sin sede';
    }

    public function getCarrera()
    {
        $convenio = $this->campoClinico
            ->getConvenio();

        return $convenio->getCarrera() !== null ?
            $convenio->getCarrera()
                ->getDisplayName():
            'Sin carrera';
    }

    public function getTipoCampoClinico() {
        return $this->campoClinico
                ->getConvenio()
                ->getCicloAcademico()
                ->getId();
    }

    public function getCicloAcademico() {
        return $this->campoClinico->getDisplayCicloAcademico();
    }
}
