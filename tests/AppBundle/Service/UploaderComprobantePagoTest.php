<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Repository\PagoRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UploaderComprobantePagoTest extends WebTestCase
{
    /**
     * @var PagoRepositoryInterface
     */
    private $pagoRepository;

    protected function setUp()
    {
        $client = self::createClient();
        $container = $client->getContainer();

        $this->pagoRepository = $container->get(PagoRepositoryInterface::class);
    }

    public function testUploadComprobanteSuccessfully()
    {
        $referenciaBancaria = 1000001;

        $pago = new Pago();
        $pago->setMonto(10000);
        $pago->setReferenciaBancaria($referenciaBancaria);
        $pago->setRequiereFactura(false);
        $pago->setComprobantePago('comprobante.pdf');
        $pago->setValidado(false);
        $pago->setObservaciones('');
        $this->pagoRepository->save($pago);

        $service = new UploaderComprobantePago(
            $this->pagoRepository
        );

        $campoClinico = $this->createMock(CampoClinico::class);
        $campoClinico->method('getReferenciaBancaria')
            ->willReturn($referenciaBancaria);

        $this->assertTrue(true);
    }
}

class UploaderComprobantePago implements UploaderComprobantePagoInterface
{
    /**
     * @var PagoRepositoryInterface
     */
    private $pagoRepository;

    public function __construct(PagoRepositoryInterface $pagoRepository)
    {
        $this->pagoRepository = $pagoRepository;
    }

    public function update(CampoClinico $campoClinico)
    {
        /** @var Pago $comprobante */
        $comprobante = $this->pagoRepository->getComprobante($campoClinico->getReferenciaBancaria());

    }
}

interface UploaderComprobantePagoInterface
{
    public function update(CampoClinico $campoClinico);
}
