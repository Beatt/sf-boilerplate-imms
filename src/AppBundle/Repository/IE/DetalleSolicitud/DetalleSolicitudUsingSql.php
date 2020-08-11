<?php

namespace AppBundle\Repository\IE\DetalleSolicitud;

use AppBundle\Entity\Pago;
use AppBundle\ObjectValues\SolicitudId;
use AppBundle\Repository\IE\DetalleSolicitud\Expediente\Expediente;
use AppBundle\Repository\IE\DetalleSolicitud\ListaCamposClinicos\CamposClinicos;
use AppBundle\Repository\IE\DetalleSolicitud\TotalCamposClinicosAutorizados\TotalCamposClinicos;
use AppBundle\Repository\SolicitudRepositoryInterface;

final class DetalleSolicitudUsingSql implements DetalleSolicitud
{
    private $solicitudRepository;

    private $camposClinicos;

    private $totalCamposClinicos;

    private $expediente;

    public function __construct(
        SolicitudRepositoryInterface $solicitudRepository,
        CamposClinicos $camposClinicos,
        TotalCamposClinicos $totalCamposClinicos,
        Expediente $expediente
    ) {
        $this->camposClinicos = $camposClinicos;
        $this->totalCamposClinicos = $totalCamposClinicos;
        $this->solicitudRepository = $solicitudRepository;
        $this->expediente = $expediente;
    }

    public function detalleBySolicitud(SolicitudId $solicitudId)
    {
        /** @var \AppBundle\Entity\Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($solicitudId->asInt());
        $camposClinicos = $this->camposClinicos->listaCamposClinicosBySolicitud($solicitudId);
        $totalCamposAutorizados = $this->totalCamposClinicos->totalCamposClinicosAutorizados($solicitudId);
        $documents = $this->expediente->expedienteBySolicitud($solicitudId);
        $ultimoPago = $this->getLastPago($solicitud);

        return new Solicitud(
            $solicitud->getId(),
            $solicitud->getEstatus(),
            $solicitud->getNoSolicitud(),
            $camposClinicos,
            $totalCamposAutorizados->getTotal(),
            $documents,
            $ultimoPago
        );
    }

    /**
     * @param \AppBundle\Entity\Solicitud $solicitud
     * @return UltimoPago
     */
    private function getLastPago(\AppBundle\Entity\Solicitud $solicitud)
    {
        $ultimoPago = new UltimoPago();
        /** @var Pago $lastPago */
        if (!$solicitud->getPagos()->isEmpty()) {
          $lastPago = $solicitud->getPagos()->last();
          $ultimoPago = new UltimoPago($lastPago->getId());
        }
        return $ultimoPago;
    }
}
