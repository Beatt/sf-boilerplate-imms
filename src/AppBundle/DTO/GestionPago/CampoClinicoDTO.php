<?php

namespace AppBundle\DTO\GestionPago;

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
                ->getNombre() :
            'Sin carrera';
    }
}
