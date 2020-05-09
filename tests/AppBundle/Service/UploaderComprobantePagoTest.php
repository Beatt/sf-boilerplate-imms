<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Service\UploaderComprobantePago;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderComprobantePagoTest extends WebTestCase
{
    /**
     * @var PagoRepositoryInterface
     */
    private $pagoRepositoryInterface;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');
        $this->pagoRepositoryInterface = $container->get(PagoRepositoryInterface::class);
        $this->logger = $container->get('logger');
    }

    public function testUploadComprobanteSuccessfully()
    {
        $referenciaBancaria = 1000001;

        $pago = new Pago();
        $pago->setMonto(10000);
        $pago->setReferenciaBancaria(1000001);
        $pago->setRequiereFactura(false);
        $this->pagoRepositoryInterface->save($pago);

        $campoClinico = $this->createMock(CampoClinico::class)
            ->method('getReferenciaBancaria')
            ->willReturn($referenciaBancaria);

        $service = new UploaderComprobantePago(
            $this->entityManager,
            $this->pagoRepositoryInterface,
            $this->logger
        );

        $uploadedFile = $this->createMock(UploadedFile::class);

        $service->update(
            $campoClinico,
            $uploadedFile
        );
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
