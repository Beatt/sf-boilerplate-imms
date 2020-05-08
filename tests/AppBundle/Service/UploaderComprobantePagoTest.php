<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Service\UploaderComprobantePago;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderComprobantePagoTest extends WebTestCase
{
    /**
     * @var PagoRepositoryInterface
     */
    private $pagoRepositoryInterface;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $this->pagoRepositoryInterface = $container->get(PagoRepositoryInterface::class);
    }

    public function testUploadComprobanteSuccessfully()
    {
        $referenciaBancaria = 1000001;

        $pago = new Pago();
        $pago->setMonto(10000);
        $pago->setReferenciaBancaria(1000001);
        $pago->setRequiereFactura(false);
        $this->pagoRepositoryInterface->save($pago);

        $service = new UploaderComprobantePago($this->pagoRepositoryInterface);

        $campoClinico = $this->createMock(CampoClinico::class);
        $campoClinico->method('getReferenciaBancaria')
            ->willReturn($referenciaBancaria);

        $file = new File(__DIR__.'/test.pdf');
        $service->update(
            $campoClinico,
            new UploadedFile($file->getRealPath(), $file->getFilename(), $file->getMimeType(), $file->getSize(), null, true)
        );

        $this->assertNotNull($pago->getComprobantePago());
    }

    public function tearDown()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');

        $purger = new ORMPurger($doctrine->getManager());
        $purger->purge();
    }
}
