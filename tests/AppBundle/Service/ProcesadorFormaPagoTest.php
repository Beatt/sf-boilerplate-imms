<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Service\GeneradorReferenciaBancaria;
use AppBundle\Service\ProcesadorFormaPago;
use Tests\AppBundle\AbstractWebTestCase;

class ProcesadorFormaPagoTest extends AbstractWebTestCase
{
    /**
     * @var SolicitudRepositoryInterface
     */
    private $solicitudRepository;

    /**
     * @var PagoRepositoryInterface
     */
    private $pagoRepository;

    /**
     * @var CampoClinicoRepositoryInterface
     */
    private $campoClinicoRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->solicitudRepository = $this->container->get(SolicitudRepositoryInterface::class);
        $this->pagoRepository = $this->container->get(PagoRepositoryInterface::class);
        $this->campoClinicoRepository = $this->container->get(CampoClinicoRepositoryInterface::class);

        $this->settingDefaultValuesToSolicitud();
    }

    public function testPagarSolicitudPorPagoUnico()
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([
            'estatus' => SolicitudInterface::MONTOS_VALIDADOS_CAME
        ]);
        $solicitud->setTipoPago(Solicitud::TIPO_PAGO_UNICO);

        $generadorReferenciaBancaria = $this->createMock(GeneradorReferenciaBancaria::class);
        $generadorReferenciaBancaria
            ->method('getReferenciaBancaria')
            ->willReturn('1000001');

        /** @var GeneradorReferenciaBancaria $generadorReferenciaBancaria */
        $procesadorFormaPago = new ProcesadorFormaPago(
            $this->container->get('doctrine.orm.default_entity_manager'),
            $generadorReferenciaBancaria
        );
        $procesadorFormaPago->procesar($solicitud);

        /** @var Pago $pago */
        $pago = $this->pagoRepository->findOneBy([
            'solicitud' => $solicitud->getId()
        ]);

        $this->assertNotNull($pago->getId());
        $this->assertEquals('1000001', $pago->getReferenciaBancaria());
        $this->assertEquals('1000001', $solicitud->getReferenciaBancaria());
        /** @var CampoClinico $camposClinico */
        foreach($solicitud->getCamposClinicos() as $camposClinico) {
            $this->assertNull($camposClinico->getReferenciaBancaria());
        }
        $this->assertEquals(SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS, $solicitud->getEstatus());
    }

    public function testPagarSolicitudPorPagoMultiple()
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([
            'estatus' => SolicitudInterface::MONTOS_VALIDADOS_CAME
        ]);
        $solicitud->setTipoPago(SolicitudTipoPagoInterface::TIPO_PAGO_MULTIPLE);

        $generadorReferenciaBancaria = $this->createMock(GeneradorReferenciaBancaria::class);
        $generadorReferenciaBancaria
            ->method('getReferenciaBancaria')
            ->willReturn('1000001');

        /** @var GeneradorReferenciaBancaria $generadorReferenciaBancaria */
        $procesadorFormaPago = new ProcesadorFormaPago(
            $this->container->get('doctrine.orm.default_entity_manager'),
            $generadorReferenciaBancaria
        );
        $procesadorFormaPago->procesar($solicitud);

        $camposClinicos = $this->campoClinicoRepository->findBy([
            'solicitud' => $solicitud->getId()
        ]);

        /** @var CampoClinico $camposClinico */
        foreach($camposClinicos as $camposClinico) {
            $this->assertEquals(
                EstatusCampoInterface::PENDIENTE_DE_PAGO,
                $camposClinico->getEstatus()->getNombre()
            );

            $pago = $this->pagoRepository->findOneBy([
                'referenciaBancaria' => $camposClinico->getReferenciaBancaria()
            ]);

            $this->assertNotNull($pago->getId());
            $this->assertEquals('1000001', $pago->getReferenciaBancaria());
        }
        $this->assertNull($solicitud->getReferenciaBancaria());
        $this->assertEquals(SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS, $solicitud->getEstatus());
    }

    protected function settingDefaultValuesToSolicitud()
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([
            'estatus' => SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS
        ]);
        $solicitud->setEstatus(SolicitudInterface::MONTOS_VALIDADOS_CAME);
        $solicitud->setTipoPago(null);
        $solicitud->setReferenciaBancaria(null);
        $camposClinicos = $solicitud->getCamposClinicos();
        /** @var CampoClinico $camposClinico */
        foreach($camposClinicos as $camposClinico) {
            $camposClinico->setReferenciaBancaria(null);
            $camposClinico->setMonto(0);
        }
        $this->entityManager->flush();

        $this->clearTablaPagoById($solicitud->getId());
    }
}
