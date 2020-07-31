<?php

namespace AppBundle\Repository\IE\DetalleSolicitudMultiple;

use AppBundle\ObjectValues\SolicitudId;
use AppBundle\Repository\IE\DetalleSolicitud\Solicitud;
use AppBundle\Repository\IE\DetalleSolicitud\TotalCamposClinicosAutorizados\TotalCamposClinicos;
use AppBundle\Repository\IE\DetalleSolicitud\UltimoPago;
use AppBundle\Repository\IE\DetalleSolicitudMultiple\Expediente\Expediente;
use AppBundle\Repository\IE\DetalleSolicitudMultiple\ListaCamposClinicos\CamposClinicos;
use AppBundle\Repository\SolicitudRepositoryInterface;

final class DetalleSolicitudMultipleUsingSql implements DetalleSolicitudMultiple
{
    private $solicitudRepository;

    private $totalCamposClinicos;

    private $expediente;

    private $camposClinicos;

    public function __construct(
        SolicitudRepositoryInterface $solicitudRepository,
        TotalCamposClinicos $totalCamposClinicos,
        Expediente $expediente,
        CamposClinicos $camposClinicos
    ) {
        $this->totalCamposClinicos = $totalCamposClinicos;
        $this->solicitudRepository = $solicitudRepository;
        $this->expediente = $expediente;
        $this->camposClinicos = $camposClinicos;
    }

    public function getDetalleBySolicitud(SolicitudId $solicitudId)
    {
        /** @var \AppBundle\Entity\Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($solicitudId->asInt());
        $totalCamposAutorizados = $this->totalCamposClinicos->totalCamposClinicosAutorizados($solicitudId);
        $documents = $this->expediente->expedienteBySolicitud($solicitudId);
        $camposClinicos = $this->camposClinicos->listaCamposClinicosBySolicitud($solicitudId);

        return new Solicitud(
            $solicitud->getId(),
            $solicitud->getEstatus(),
            $solicitud->getNoSolicitud(),
            $camposClinicos,
            $totalCamposAutorizados->getTotal(),
            $documents,
            new UltimoPago()
        );
    }
}
