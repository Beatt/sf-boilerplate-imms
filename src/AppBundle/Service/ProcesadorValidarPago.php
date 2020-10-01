<?php

namespace AppBundle\Service;

use AppBundle\Calculator\ComprobantePagoCalculatorInterface;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Event\PagoEvent;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\EstatusCampoRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class ProcesadorValidarPago implements ProcesadorValidarPagoInterface
{
    private $entityManager;

    private $calculator;

    private $estatusCampoRepository;

    private $campoClinicoRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

  /**
   * ProcesadorValidarPago constructor.
   * @param EntityManagerInterface $entityManager
   * @param ComprobantePagoCalculatorInterface $calculator
   * @param EstatusCampoRepositoryInterface $estatusCampoRepository
   * @param CampoClinicoRepositoryInterface $campoClinicoRepository
   * @param EventDispatcherInterface $dispatcher
   */
  public function __construct(
        EntityManagerInterface $entityManager,
        ComprobantePagoCalculatorInterface $calculator,
        EstatusCampoRepositoryInterface $estatusCampoRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        EventDispatcherInterface $dispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->calculator = $calculator;
        $this->estatusCampoRepository = $estatusCampoRepository;
        $this->campoClinicoRepository = $campoClinicoRepository;
        $this->dispatcher = $dispatcher;
    }

    public function procesar(Pago $pago)
    {
        $solicitud = $pago->getSolicitud();

        if($solicitud->isPagoUnico()) {
            if($pago->isValidado()) {
                $this->updateEstadoCamposClinicosPorPagoUnico($pago, $solicitud);
                if(!$pago->isRequiereFactura()) $this->setEstatusCredencialesGeneradasToSolicitud($solicitud);
            } else {
                $this->updateEstadoCamposClinicosAPagoNoValido($solicitud);
                $this->setEstatusPagoNoValidoToSolicitud($solicitud);
            }
        }
        else {
            if($pago->isValidado()) {
                $this->updateEstatusCampoClinicoPorPagoMultiple($pago);
                if(!$this->existenCamposConEstatusDiferenteACredencialesGeneradas($solicitud)){
                    $this->setEstatusCredencialesGeneradasToSolicitud($solicitud);
                }
            } else {
                $camposClinico = $this->getCampoClinicoActual($pago);
                $estatus = $this->getEstatusCampoNoValido();
                $camposClinico->setEstatus($estatus);
            }
        }

        if(!$pago->isValidado()) $this->createPago($pago);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(
          $pago->isValidado() ? PagoEvent::PAGO_VALIDADO : PagoEvent::PAGO_INCORRECTO,
          new PagoEvent($pago)
        );
    }

    /**
     * @param Pago $pago
     * @param Solicitud $solicitud
     */
    private function updateEstadoCamposClinicosPorPagoUnico(
        Pago $pago,
        Solicitud $solicitud
    )
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
        $camposClinico = $this->getCampoClinicoActual($pago);
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

    /**
     * @param Solicitud $solicitud
     */
    private function updateEstadoCamposClinicosAPagoNoValido(Solicitud $solicitud)
    {
        array_map(function (CampoClinico $campoClinico) {
            /** @var EstatusCampo $estatus */
            $estatus = $this->getEstatusCampoNoValido();
            $campoClinico->setEstatus($estatus);
        }, $solicitud->getCamposClinicos()->toArray());
    }

    /**
     * @return object
     */
    private function getEstatusCampoNoValido()
    {
        return $this->estatusCampoRepository->findOneBy([
            'nombre' => EstatusCampoInterface::PAGO_NO_VALIDO
        ]);
    }

    /**
     * @param Solicitud $solicitud
     * @return Solicitud
     */
    private function setEstatusCredencialesGeneradasToSolicitud(Solicitud $solicitud)
    {
        return $solicitud->setEstatus(SolicitudInterface::CREDENCIALES_GENERADAS);
    }

    /**
     * @param Solicitud $solicitud
     * @return Solicitud
     */
    private function setEstatusPagoNoValidoToSolicitud(Solicitud $solicitud)
    {
        return $solicitud->setEstatus(SolicitudInterface::CARGANDO_COMPROBANTES);
    }

    /**
     * @param Solicitud $solicitud
     * @return int
     */
    private function existenCamposConEstatusDiferenteACredencialesGeneradas(Solicitud $solicitud)
    {
        return count(array_filter($solicitud->getCamposClinicos()->toArray(), function (CampoClinico $campoClinico) {
            $estatus =$campoClinico->getEstatus();
            return $estatus && $estatus->getNombre() !== EstatusCampoInterface::CREDENCIALES_GENERADAS;
        })) !== 0;
    }

    /**
     * @param Pago $pago
     * @return object
     */
    private function getCampoClinicoActual(Pago $pago)
    {
        return $this->campoClinicoRepository->findOneBy([
            'referenciaBancaria' => $pago->getReferenciaBancaria()
        ]);
    }
}
