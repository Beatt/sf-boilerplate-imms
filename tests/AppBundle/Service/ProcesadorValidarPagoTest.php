<?php

namespace Tests\AppBundle\Service;

use AppBundle\DataFixtures\CampoClinicoFixture;
use AppBundle\DataFixtures\SolicitudFixture;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use AppBundle\Repository\PagoRepositoryInterface;
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
     * @var PagoRepositoryInterface
     */
    private $pagoRepository;

    /**
     * @var CommandTester
     */
    private $commandTester;

    protected function setUp()
    {
        parent::setUp();

        $this->procesadorValidarPago = $this->container->get(ProcesadorValidarPagoInterface::class);
        $this->solicitudRepository = $this->container->get(SolicitudRepositoryInterface::class);
        $this->pagoRepository = $this->container->get(PagoRepositoryInterface::class);

        $application = new Application(self::$kernel);
        $command = $application->find('doctrine:fixtures:load');
        $this->commandTester = new CommandTester($command);
    }



    public function testValidarPagoPorTipoDePagoUnico()
    {
        $this->commandTester->execute(['--append' => true]);

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy([
            'estatus' => SolicitudInterface::EN_VALIDACION_FOFOE
        ]);

        /** @var Pago $pago */
        $pago = $solicitud->getPagos()->first();
        $this->procesadorValidarPago->procesar($pago);

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($solicitud->getId());

        $this->assertEquals(
            SolicitudInterface::EN_VALIDACION_FOFOE,
            $solicitud->getEstatus()
        );

        /** @var CampoClinico $camposClinico  */
        foreach($solicitud->getCamposClinicos() as $camposClinico) {
            dump($camposClinico->getEstatus());
           /* $this->assertEquals(
                EstatusCampoInterface::PENDIENTE_FACTURA_FOFOE,
                $camposClinico->getEstatus()->getNombre()
            );*/
        }
    }

    private function createSolicitud()
    {
        $solicitud = new Solicitud();
        $solicitud->setEstatus(SolicitudInterface::EN_VALIDACION_FOFOE);
        $solicitud->setMonto(0);
        $solicitud->setNoSolicitud('Ns00000');
    }


}
