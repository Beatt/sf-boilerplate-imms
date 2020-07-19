<?php

namespace AppBundle\Service;

use AppBundle\Calculator\ComprobantePagoCalculatorInterface;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\EstatusCampoRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ProcesadorValidarPago implements ProcesadorValidarPagoInterface
{
    private $entityManager;

    private $calculator;

    private $estatusCampoRepository;

    private $campoClinicoRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ComprobantePagoCalculatorInterface $calculator,
        EstatusCampoRepositoryInterface $estatusCampoRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository
    ) {
        $this->entityManager = $entityManager;
        $this->calculator = $calculator;
        $this->estatusCampoRepository = $estatusCampoRepository;
        $this->campoClinicoRepository = $campoClinicoRepository;
    }

    public function procesar(Pago $pago)
    {
        $solicitud = $pago->getSolicitud();

        if($solicitud->isPagoUnico()) {
            if(!$pago->isRequiereFactura()) $solicitud->setEstatus(SolicitudInterface::CREDENCIALES_GENERADAS);
            $this->updateEstadoCamposClinicosPorPagoUnico($pago, $solicitud);
        }
        else {
            $this->updateEstatusCampoClinicoPorPagoMultiple($pago);
        }

        if(!$pago->isValidado()) {
            $this->createPago($pago);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Pago $pago
     * @param Solicitud $solicitud
     */
    private function updateEstadoCamposClinicosPorPagoUnico(Pago $pago, Solicitud $solicitud)
    {
        array_map(function (CampoClinico $campoClinico) use ($pago) {
            $estatus = $this->getEstatusCampoByPagoValidado($pago);
            $campoClinico->setEstatus($estatus);
        }, $solicitud->getCamposClinicos()->toArray());
    }

    /**
     * @param Pago $pago
     * @return EstatusCampo|object
     */
    private function getEstatusCampoByPagoValidado(Pago $pago)
    {
        $estatus = null;

        return $this->estatusCampoRepository->findOneBy([
            'nombre' => $pago->isRequiereFactura() ?
                EstatusCampoInterface::PENDIENTE_FACTURA_FOFOE :
                EstatusCampoInterface::CREDENCIALES_GENERADAS
        ]);
    }

    /**
     * @param Pago $pago
     */
    private function updateEstatusCampoClinicoPorPagoMultiple(Pago $pago)
    {
        $camposClinico = $this->campoClinicoRepository->findOneBy([
            'referenciaBancaria' => $pago->getReferenciaBancaria()
        ]);

        $estatus = $this->getEstatusCampoByPagoValidado($pago);
        $camposClinico->setEstatus($estatus);
    }

    /**
     * @param Pago $pago
     */
    private function createPago(Pago $pago)
    {
        $newPago = new Pago();
        $newPago->setRequiereFactura(false);
        $newPago->setSolicitud($pago->getSolicitud());
        $newPago->setReferenciaBancaria($pago->getReferenciaBancaria());
        $newPago->setMonto($this->calculator->getMontoAPagar($pago));
        $pago->getSolicitud()->addPago($pago);
        $this->entityManager->persist($newPago);
    }
}
