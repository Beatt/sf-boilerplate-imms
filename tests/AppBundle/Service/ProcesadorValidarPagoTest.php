<?php

namespace Tests\AppBundle\Service;

use AppBundle\Calculator\ComprobantePagoCalculatorInterface;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Service\ProcesadorValidarPagoInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\AppBundle\AbstractWebTestCase;

class ProcesadorValidarPagoTest extends AbstractWebTestCase
{
    /**
     * @var ProcesadorValidarPagoInterface
     */
    private $procesadorValidarPago;

    /**
     * @var SolicitudRepositoryInterface
     */
    private $solicitudRepository;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @var CampoClinicoRepositoryInterface
     */
    private $campoClinicoRepository;

    /**
     * @var ComprobantePagoCalculatorInterface
     */
    private $comprobantePagoCalculator;

    protected function setUp()
    {
        parent::setUp();

        $this->procesadorValidarPago = $this->container->get(ProcesadorValidarPagoInterface::class);
        $this->solicitudRepository = $this->container->get(SolicitudRepositoryInterface::class);
        $this->campoClinicoRepository = $this->container->get(CampoClinicoRepositoryInterface::class);
        $this->comprobantePagoCalculator = $this->container->get(ComprobantePagoCalculatorInterface::class);

        $application = new Application(self::$kernel);
        $command = $application->find('hautelook_alice:fixtures:load');
        $this->commandTester = new CommandTester($command);
    }


    public function testLaIEHaPagadoCorrectamenteElMontoTotalPorSolicitudYSolicitoFactura()
    {
        $this->commandTester->execute(['--append' => true]);

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([
            'estatus' => SolicitudInterface::EN_VALIDACION_FOFOE,
            'tipoPago' => SolicitudTipoPagoInterface::TIPO_PAGO_UNICO
        ]);

        $monto = 30000;

        /** @var CampoClinico $campoClinico */
        $campoClinico = $solicitud->getCamposClinicos()->first();
        $campoClinico->setMonto($monto);

        /** @var Pago $pago */
        $pago = $solicitud->getPagos()->first();
        $pago->setMonto($monto);

        $this->entityManager->flush();

        $this->procesadorValidarPago->procesar($pago);

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($solicitud->getId());

        $this->assertEquals(SolicitudInterface::EN_VALIDACION_FOFOE, $solicitud->getEstatus());
        $this->assertCount(1, $solicitud->getPagos());

        /** @var CampoClinico $camposClinico  */
        foreach($solicitud->getCamposClinicos() as $camposClinico) {
            $this->assertEquals(
                EstatusCampoInterface::PENDIENTE_FACTURA_FOFOE,
                $camposClinico->getEstatus()->getNombre()
            );
        }
    }

    public function testLaIEHaPagadoCorrectamenteElMontoTotalPorCampoClinicoYSolicitoFactura()
    {
        $this->commandTester->execute(['--append' => true]);

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([
            'estatus' => SolicitudInterface::EN_VALIDACION_FOFOE,
            'tipoPago' => SolicitudTipoPagoInterface::TIPO_PAGO_MULTIPLE
        ]);

        $monto = 30000;

        /** @var CampoClinico $campoClinico */
        $campoClinico = $solicitud->getCamposClinicos()->first();
        $campoClinico->setMonto($monto);

        /** @var Pago $pago */
        $pago = $solicitud->getPagos()->first();
        $pago->setMonto($monto);

        $this->entityManager->flush();

        $this->procesadorValidarPago->procesar($pago);

        /** @var CampoClinico $campoClinico */
        $campoClinico = $this->campoClinicoRepository->findOneBy([
            'referenciaBancaria' => $pago->getReferenciaBancaria()
        ]);

        $this->assertEquals(SolicitudInterface::EN_VALIDACION_FOFOE, $campoClinico->getSolicitud()->getEstatus());
        $this->assertCount(1, $campoClinico->getPagos());
        $this->assertEquals(EstatusCampoInterface::PENDIENTE_FACTURA_FOFOE, $campoClinico->getEstatus()->getNombre());
        $this->assertEquals(
            EstatusCampoInterface::PAGO,
            $campoClinico->getSolicitud()->getCamposClinicos()->last()->getEstatus()->getNombre()
        );
    }

    public function testLaIEHaPagadoCorrectamenteElMontoTotalPorSolicitudYNoSolicitoFactura()
    {
        $this->commandTester->execute(['--append' => true]);

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([
            'estatus' => SolicitudInterface::EN_VALIDACION_FOFOE,
            'tipoPago' => SolicitudTipoPagoInterface::TIPO_PAGO_UNICO
        ]);

        $monto = 30000;

        /** @var CampoClinico $campoClinico */
        $campoClinico = $solicitud->getCamposClinicos()->first();
        $campoClinico->setMonto($monto);

        /** @var Pago $pago */
        $pago = $solicitud->getPagos()->first();
        $pago->setMonto($monto);
        $pago->setRequiereFactura(false);

        $this->entityManager->flush();

        $this->procesadorValidarPago->procesar($pago);

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($solicitud->getId());

        $this->assertEquals(SolicitudInterface::CREDENCIALES_GENERADAS, $solicitud->getEstatus());
        $this->assertCount(1, $solicitud->getPagos());

        /** @var CampoClinico $camposClinico  */
        foreach($solicitud->getCamposClinicos() as $camposClinico) {
            $this->assertEquals(
                EstatusCampoInterface::CREDENCIALES_GENERADAS,
                $camposClinico->getEstatus()->getNombre()
            );
        }
    }

    public function testLaIENoHaPagadoCorrectamenteElMontoTotalPorSolicitud()
    {
        $this->commandTester->execute(['--append' => true]);

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([
            'estatus' => SolicitudInterface::EN_VALIDACION_FOFOE,
            'tipoPago' => SolicitudTipoPagoInterface::TIPO_PAGO_UNICO
        ]);

        $montoAPagar = 30000;
        $montoPagado = 10000;

        $solicitud->setMonto($montoAPagar);

        /** @var Pago $pago */
        $pago = $solicitud->getPagos()->first();
        $pago->setMonto($montoPagado);
        $pago->setValidado(false);

        $this->entityManager->flush();

        $this->procesadorValidarPago->procesar($pago);

        $this->entityManager->detach($solicitud);
        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($solicitud->getId());

        $this->assertEquals(SolicitudInterface::EN_VALIDACION_FOFOE, $solicitud->getEstatus());
        $this->assertCount(2, $solicitud->getPagos());
        $this->assertEquals(20000, $solicitud->getPagos()[1]->getMonto());

        /** @var CampoClinico $camposClinico  */
        foreach($solicitud->getCamposClinicos() as $camposClinico) {
            $this->assertEquals(
                EstatusCampoInterface::PENDIENTE_FACTURA_FOFOE,
                $camposClinico->getEstatus()->getNombre()
            );
        }
    }
}
