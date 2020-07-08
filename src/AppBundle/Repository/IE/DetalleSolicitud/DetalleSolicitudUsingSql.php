<?php

namespace AppBundle\Repository\IE\DetalleSolicitud;

use AppBundle\ObjectValues\SolicitudId;
use AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos\CamposClinicos;
use AppBundle\Repository\IE\DetalleSolicitud\TotalCamposClinicosAutorizados\TotalCamposClinicos;
use AppBundle\Repository\SolicitudRepositoryInterface;

final class DetalleSolicitudUsingSql implements DetalleSolicitud
{
    private $solicitudRepository;

    private $camposClinicos;

    private $totalCamposClinicos;

    public function __construct(
        SolicitudRepositoryInterface $solicitudRepository,
        CamposClinicos $camposClinicos,
        TotalCamposClinicos $totalCamposClinicos
    ) {
        $this->camposClinicos = $camposClinicos;
        $this->totalCamposClinicos = $totalCamposClinicos;
        $this->solicitudRepository = $solicitudRepository;
    }

    public function detalleBySolicitud(SolicitudId $solicitudId)
    {
        /** @var \AppBundle\Entity\Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($solicitudId->asInt());
        $camposClinicos = $this->camposClinicos->listaCamposClinicosBySolicitud($solicitudId);
        $totalCamposAutorizados = $this->totalCamposClinicos->totalCamposClinicosAutorizados($solicitudId);

        return new Solicitud(
            $solicitud->getId(),
            $solicitud->getEstatus(),
            $solicitud->getNoSolicitud(),
            $camposClinicos,
            $totalCamposAutorizados->getTotal()
        );
    }
}
