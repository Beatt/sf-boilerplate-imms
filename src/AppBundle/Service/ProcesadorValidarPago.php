<?php

namespace AppBundle\Service;

use AppBundle\Calculator\ComprobantePagoCalculatorInterface;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Pago;
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
            /** @var CampoClinico $camposClinico */
            foreach($solicitud->getCamposClinicos() as $camposClinico) {
                $estatus = $this->getEstatusByPagoValidado($pago);
                $camposClinico->setEstatus($estatus);
            }
        }
        else {
            $this->actualizarEstatusDeCampoClinicoActual($pago);
        }

        if(!$pago->isValidado()) {
            $this->createPago($pago);
        }

        $this->entityManager->flush();
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
        $this->entityManager->persist($newPago);
    }

    /**
     * @param Pago $pago
     */
    private function actualizarEstatusDeCampoClinicoActual(Pago $pago)
    {
        $camposClinico = $this->campoClinicoRepository->findOneBy([
            'referenciaBancaria' => $pago->getReferenciaBancaria()
        ]);

        $estatus = $this->getEstatusByPagoValidado($pago);
        $camposClinico->setEstatus($estatus);
    }

    /**
     * @param Pago $pago
     * @return EstatusCampo
     */
    private function getEstatusByPagoValidado(Pago $pago)
    {
        /** @var EstatusCampo $estatus */
        $estatus = $this->estatusCampoRepository->findOneBy([
            'nombre' => EstatusCampoInterface::PENDIENTE_FACTURA_FOFOE
        ]);

        if (!$pago->isValidado()) {
            /** @var EstatusCampo $estatus */
            $estatus = $this->estatusCampoRepository->findOneBy([
                'nombre' => EstatusCampoInterface::PAGO_NO_VALIDO
            ]);
        }
        return $estatus;
    }
}
