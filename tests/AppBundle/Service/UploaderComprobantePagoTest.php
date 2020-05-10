<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Convenio;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\EstatusCampoRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Service\UploaderComprobantePago;
use Carbon\Carbon;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\AppBundle\AbstractWebTestCase;

class UploaderComprobantePagoTest extends AbstractWebTestCase
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
     * @var EstatusCampoRepositoryInterface
     */
    private $estatusCampoRepository;

    /**
     * @var CampoClinicoRepositoryInterface
     */
    private $campoClinicoRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->pagoRepositoryInterface = $this->container->get(PagoRepositoryInterface::class);
        $this->estatusCampoRepository = $this->container->get(EstatusCampoRepositoryInterface::class);
        $this->campoClinicoRepository = $this->container->get(CampoClinicoRepositoryInterface::class);
        $this->logger = $this->container->get('logger');

        $this->clearTablaPago();
        $this->clearTablaCampoClinico();
        $this->clearTablaSolicitud();

        copy(__DIR__ . '/pdf.pdf', __DIR__ . '/pdf-test.pdf');
    }

    public function testGuardarComprobantePagoCorrectamente()
    {
        $referenciaBancaria = 1000001;

        /** @var Convenio $convenio */
        $convenio = $this->entityManager->getRepository(Convenio::class)
            ->findOneBy([]);

        $solicitud = $this->createSolicitud($referenciaBancaria);
        $this->createPago($referenciaBancaria, $solicitud);

        $campoClinico = $this->createCampoClinico(
            $referenciaBancaria,
            $solicitud,
            $convenio,
            EstatusCampoInterface::PENDIENTE_DE_PAGO
        );

        $this->entityManager->flush();

        $file = new File(__DIR__ . '/pdf-test.pdf');
        $uploadedFile = new UploadedFile(
            $file->getRealPath(),
            $file->getFilename(),
            $file->getMimeType(),
            $file->getSize(),
            null,
            true
        );

        $service = new UploaderComprobantePago(
            $this->entityManager,
            $this->pagoRepositoryInterface,
            $this->logger
        );

        $service->update(
            $campoClinico,
            $uploadedFile
        );

        /** @var CampoClinico $campoClinico */
        $campoClinico = $this->campoClinicoRepository->findOneBy(['referenciaBancaria' => $referenciaBancaria]);

        /** @var Pago $pago */
        $pago = $this->pagoRepositoryInterface->findOneBy(['referenciaBancaria' => $referenciaBancaria]);

        $this->assertEquals(EstatusCampoInterface::PAGO, $campoClinico->getEstatus()->getNombre());
        $this->assertNotNull($pago->getComprobantePago());
    }

    /**
     * @param $referenciaBancaria
     * @param Solicitud $solicitud
     * @param Convenio $convenio
     * @param int $monto
     * @param string $estatus
     * @return CampoClinico
     */
    private function createCampoClinico(
        $referenciaBancaria,
        Solicitud $solicitud,
        Convenio $convenio,
        $estatus = null,
        $monto = null
    ) {

        $estatus = $estatus ?: EstatusCampoInterface::NUEVO;
        $estatus = $this->estatusCampoRepository->findOneBy(['nombre' => $estatus]);
        $monto = $monto ?: 10000;

        $campoClinico = new CampoClinico();
        $campoClinico->setReferenciaBancaria($referenciaBancaria);
        $campoClinico->setMonto($monto);
        $campoClinico->setEstatus($estatus);
        $campoClinico->setLugaresSolicitados(10);
        $campoClinico->setLugaresAutorizados(10);
        $campoClinico->setPromocion('promicion');
        $campoClinico->setHorario('10am a 5pm');
        $campoClinico->setFechaInicial(Carbon::now());
        $campoClinico->setFechaFinal(Carbon::now()->addMonths(3));
        $campoClinico->setSolicitud($solicitud);
        $campoClinico->setConvenio($convenio);
        $this->entityManager->persist($campoClinico);
        return $campoClinico;
    }

    /**
     * @param $referenciaBancaria
     * @return Solicitud
     */
    private function createSolicitud($referenciaBancaria)
    {
        $solicitud = new Solicitud();
        $solicitud->setMonto(10000);
        $solicitud->setReferenciaBancaria($referenciaBancaria);
        $solicitud->setNoSolicitud('00001');
        $solicitud->setEstatus(SolicitudInterface::CARGANDO_COMPROBANTES);
        $this->entityManager->persist($solicitud);
        return $solicitud;
    }

    /**
     * @param $referenciaBancaria
     * @param Solicitud $solicitud
     * @return Pago
     */
    private function createPago($referenciaBancaria, Solicitud $solicitud)
    {
        $pago = new Pago();
        $pago->setMonto(10000);
        $pago->setReferenciaBancaria($referenciaBancaria);
        $pago->setRequiereFactura(false);
        $pago->setSolicitud($solicitud);
        $this->entityManager->persist($pago);
        return $pago;
    }
}
