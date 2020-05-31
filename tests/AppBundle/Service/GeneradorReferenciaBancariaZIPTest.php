<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Solicitud;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Service\GeneradorReferenciaBancariaZIPInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\AbstractWebTestCase;

class GeneradorReferenciaBancariaZIPTest extends AbstractWebTestCase
{
    /**
     * @var GeneradorReferenciaBancariaZIPInterface
     */
    private $generadorReferenciaBancariaZip;

    /**
     * @var SolicitudRepositoryInterface
     */
    private $solicitudRepository;

    /**
     * @var string
     */
    private $directoryOutput;

    /**
     * @var string
     */
    private $rootDir;

    protected function setUp()
    {
        parent::setUp();

        $this->generadorReferenciaBancariaZip = $this->container->get(GeneradorReferenciaBancariaZIPInterface::class);
        $this->solicitudRepository = $this->container->get(SolicitudRepositoryInterface::class);
        $this->directoryOutput = $this->container->getParameter('referencias_bancarias_dir');
        $this->rootDir = $this->container->getParameter('kernel.root_dir');
    }

    public function testCreateZIPSuccessfully()
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findAll()[0];

        /** @var Response $response */
        $response = $this->generadorReferenciaBancariaZip->generarZipResponse($solicitud);

        $filesystem = new Filesystem();

        $this->assertTrue($response->isOk());
        $this->assertTrue($response->headers->get('Content-length') !== 0);
        $this->assertFalse($filesystem->exists($this->directoryOutput));
        $this->assertFalse ($filesystem->exists($this->rootDir . '/../ReferenciasBancarias.zip'));
    }
}
