<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

use AppBundle\ObjectValues\PagoId;
use AppBundle\Repository\CampoClinicoRepository;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;

final class DetallePagoUsingDoctrine implements DetallePago
{
    private $pagoRepository;

    private $institucionRepository;

    public function __construct(
        PagoRepositoryInterface $pagoRepository,
        InstitucionRepositoryInterface $institucionRepository
    ) {
        $this->pagoRepository = $pagoRepository;
        $this->institucionRepository = $institucionRepository;
    }

    public function detalleByPago(PagoId $pagoId)
    {
        /** @var \AppBundle\Entity\Pago $pago */
        $pago = $this->pagoRepository->find($pagoId->asInt());
        $solicitud = $pago->getSolicitud();
        $montoTotal = $this->getMontoTotal($solicitud, $pago);

        $sede = null;
        $carrera = null;
        if(!$solicitud->isPagoUnico()) {
            $campoClinico = $this->getCampoClinico($solicitud, $pago);
            $sede = $campoClinico->getUnidad()->getNombre();
            $carrera = $campoClinico->getConvenio()->getCarrera()->getNombre();
        }

        $pagos = $this->pagoRepository
            ->getComprobantesPagoValidadosByReferenciaBancaria($pago->getReferenciaBancaria());

        /** @var \AppBundle\Entity\Institucion $institucion */
        $institucion = $this->institucionRepository
            ->getInstitucionByPagoId($pago->getId());

        return new Pago(
            $pago->getId(),
            new Solicitud(
                $solicitud->getNoSolicitud(),
                $solicitud->getTipoPago(),
                new CampoClinico(
                    $sede,
                    $carrera
                )
            ),
            $montoTotal,
            $pago->getMonto(),
            $pago->getComprobantePago(),
            $pago->getFechaPago()->format('Y-m-d'),
            $pago->getMonto(),
            array_map(function (\AppBundle\Entity\Pago $pago) {
                return new PagoValidado(
                    $pago->getId(),
                    $pago->getReferenciaBancaria(),
                    $pago->getFechaPago(),
                    $pago->getMonto()
                );
            }, $pagos),
            new Institucion(
                $institucion->getNombre(),
                $institucion->getConvenios()
                    ->first()
                    ->getDelegacion()
                    ->getNombre()
            ),
            $pago->isRequiereFactura()
        );
    }

    /**
     * @param \AppBundle\Entity\Solicitud $solicitud
     * @param \AppBundle\Entity\Pago $pago
     * @return \AppBundle\Entity\CampoClinico
     */
    private function getCampoClinico(\AppBundle\Entity\Solicitud $solicitud, \AppBundle\Entity\Pago $pago)
    {
        /** @var \AppBundle\Entity\CampoClinico $campoClinico */
        $campoClinico = $solicitud->getCamposClinicos()
            ->matching(
                CampoClinicoRepository::getCampoClinicoByReferenciaBancaria(
                    $pago->getReferenciaBancaria()
                ))
            ->first();
        return $campoClinico;
    }

    /**
     * @param \AppBundle\Entity\Solicitud $solicitud
     * @param \AppBundle\Entity\Pago $pago
     * @return float
     */
    private function getMontoTotal(\AppBundle\Entity\Solicitud $solicitud, \AppBundle\Entity\Pago $pago)
    {
        $montoTotal = null;
        if ($solicitud->isPagoUnico()) {
            $montoTotal = $solicitud->getMonto();
        } else {
            $campoClinico = $this->getCampoClinico($solicitud, $pago);
            $montoTotal = $campoClinico->getMonto();
        }
        return $montoTotal;
    }
}
