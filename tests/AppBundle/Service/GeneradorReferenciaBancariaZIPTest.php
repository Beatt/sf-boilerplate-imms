<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Solicitud;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Service\GeneradorReferenciaBancariaPDFInterface;
use AppBundle\Service\GeneradorReferenciaBancariaZIP;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\AbstractWebTestCase;

class GeneradorReferenciaBancariaZIPTest extends AbstractWebTestCase
{
    /**
     * @var GeneradorReferenciaBancariaPDFInterface
     */
    private $generadorReferenciaBancariaPDF;

    /**
     * @var SolicitudRepositoryInterface
     */
    private $solicitudRepository;

    /**
     * @var string
     */
    private $directoryOutput;

    protected function setUp()
    {
        parent::setUp();

        $this->generadorReferenciaBancariaPDF = $this->container->get(GeneradorReferenciaBancariaPDFInterface::class);
        $this->solicitudRepository = $this->container->get(SolicitudRepositoryInterface::class);
        $this->directoryOutput = $this->container->getParameter('referencias_bancarias_dir');
    }

    public function testCreateZIPSuccessfully()
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findAll()[0];

        /** @var Response $response */
        $generadorReferenciaBancariaZIP = new GeneradorReferenciaBancariaZIP(
            $this->entityManager,
            $this->generadorReferenciaBancariaPDF,
            $this->directoryOutput
        );

        $response = $generadorReferenciaBancariaZIP->generarZipResponse(
            $solicitud
        );

        $filesystem = new Filesystem();

        $this->assertTrue($response->isOk());
        $this->assertFalse($filesystem->exists($this->directoryOutput));
    }
}
