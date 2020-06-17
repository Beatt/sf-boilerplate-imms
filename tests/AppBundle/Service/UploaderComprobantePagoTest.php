<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Convenio;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\EstatusCampoRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Service\UploaderComprobantePago;
use Carbon\Carbon;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\AppBundle\AbstractWebTestCase;

class UploaderComprobantePagoTest extends AbstractWebTestCase
{
    /**
     * @var PagoRepositoryInterface
     */
    private $pagoRepository;

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

    /**
     * @var SolicitudRepositoryInterface
     */
    private $solicitudRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->pagoRepository = $this->container->get(PagoRepositoryInterface::class);
        $this->estatusCampoRepository = $this->container->get(EstatusCampoRepositoryInterface::class);
        $this->campoClinicoRepository = $this->container->get(CampoClinicoRepositoryInterface::class);
        $this->solicitudRepository = $this->container->get(SolicitudRepositoryInterface::class);
        $this->logger = $this->container->get('logger');

        $this->clearTablaPago();
        $this->clearTablaCampoClinico();
        $this->clearTablaSolicitud();

        copy(__DIR__ . '/pdf.pdf', __DIR__ . '/pdf-test.pdf');
    }

    public function testGuardarComprobantePagoParaTipoDePagoUnico()
    {
        $referenciaBancaria = 1000001;

        /** @var Convenio $convenio */
        $convenio = $this->entityManager->getRepository(Convenio::class)
            ->findOneBy([]);

        $solicitud = $this->createSolicitud(
            $referenciaBancaria,
            SolicitudInterface::CARGANDO_COMPROBANTES
        );
        $this->createPago($referenciaBancaria, $solicitud);

        $this->createCampoClinico(
            null,
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
        $solicitud->getPago()->setComprobantePagoFile($uploadedFile);

        $service = new UploaderComprobantePago(
            $this->entityManager,
            $this->pagoRepository,
            $this->logger
        );

        $service->update($solicitud->getPago());

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->findOneBy(['referenciaBancaria' => $referenciaBancaria]);

        /** @var Pago $pago */
        $pago = $this->pagoRepository->findOneBy(['referenciaBancaria' => $referenciaBancaria]);

        $this->assertEquals(SolicitudInterface::EN_VALIDACION_FOFOE, $solicitud->getEstatus());
        /** @var CampoClinico $camposClinico */
        foreach($solicitud->getCamposClinicos() as $camposClinico) {
            $this->assertEquals(EstatusCampoInterface::PAGO, $camposClinico->getEstatus()->getNombre());
        }
        $this->assertNotNull($pago->getComprobantePago());
    }

    public function testGuardarComprobantePagoParaTipoDePagoMultiple()
    {
        $referenciaBancaria = 1000001;

        /** @var Convenio $convenio */
        $convenio = $this->entityManager->getRepository(Convenio::class)
            ->findOneBy([]);

        $solicitud = $this->createSolicitud(
            null,
            SolicitudInterface::CARGANDO_COMPROBANTES,
            SolicitudTipoPagoInterface::TIPO_PAGO_MULTIPLE
        );
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
            $this->pagoRepository,
            $this->logger
        );

        $service->update(
            $campoClinico,
            $uploadedFile
        );

        /** @var CampoClinico $campoClinico */
        $campoClinico = $this->campoClinicoRepository->findOneBy(['referenciaBancaria' => $referenciaBancaria]);

        /** @var Pago $pago */
        $pago = $this->pagoRepository->findOneBy(['referenciaBancaria' => $referenciaBancaria]);

        $this->assertEquals(SolicitudInterface::EN_VALIDACION_FOFOE, $campoClinico->getSolicitud()->getEstatus());
        /** @var CampoClinico $camposClinico */
        foreach($solicitud->getCamposClinicos() as $camposClinico) {
            $this->assertEquals(EstatusCampoInterface::PAGO, $camposClinico->getEstatus()->getNombre());
        }
        $this->assertNotNull($pago->getComprobantePago());
    }

    public function testNoCambiarElEstatusDeLaSolicitudDeTipoPagoMultipleSiExisteCampoClinicoSinComprobanteDePago()
    {
        $referenciaBancaria = 1000001;

        /** @var Convenio $convenio */
        $convenio = $this->entityManager->getRepository(Convenio::class)
            ->findOneBy([]);

        $solicitud = $this->createSolicitud(
            null,
            SolicitudInterface::CARGANDO_COMPROBANTES,
            SolicitudTipoPagoInterface::TIPO_PAGO_MULTIPLE
        );
        $this->createPago($referenciaBancaria, $solicitud);

        $campoClinico1 = $this->createCampoClinico(
            $referenciaBancaria,
            $solicitud,
            $convenio,
            EstatusCampoInterface::PENDIENTE_DE_PAGO
        );

        $campoClinico2 = $this->createCampoClinico(
            '1000003',
            $solicitud,
            $convenio,
            EstatusCampoInterface::PENDIENTE_DE_PAGO
        );

        $solicitud->addCamposClinico($campoClinico1);
        $solicitud->addCamposClinico($campoClinico2);
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
            $this->pagoRepository,
            $this->logger
        );

        $service->update(
            $campoClinico1,
            $uploadedFile
        );

        /** @var CampoClinico $campoClinico */
        $campoClinico = $this->campoClinicoRepository->findOneBy(['referenciaBancaria' => $referenciaBancaria]);

        /** @var Pago $pago */
        $pago = $this->pagoRepository->findOneBy(['referenciaBancaria' => $referenciaBancaria]);
        $camposClinicos = $pago->getSolicitud()->getCamposClinicos();

        $this->assertEquals(SolicitudInterface::CARGANDO_COMPROBANTES, $campoClinico->getSolicitud()->getEstatus());
        $this->assertEquals(EstatusCampoInterface::PAGO, $camposClinicos[0]->getEstatus()->getNombre());
        $this->assertEquals(EstatusCampoInterface::PENDIENTE_DE_PAGO, $camposClinicos[1]->getEstatus()->getNombre());
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
     * @param null $estatus
     * @param null $tipoPago
     * @return Solicitud
     */
    private function createSolicitud(
        $referenciaBancaria,
        $estatus = null,
        $tipoPago = null
    ) {
        $tipoPago = $tipoPago ?: SolicitudTipoPagoInterface::TIPO_PAGO_UNICO;
        $estatus = $estatus ?: SolicitudInterface::CONFIRMADA;

        $solicitud = new Solicitud();
        $solicitud->setMonto(10000);
        $solicitud->setReferenciaBancaria($referenciaBancaria);
        $solicitud->setNoSolicitud('00001');
        $solicitud->setEstatus($estatus);
        $solicitud->setTipoPago($tipoPago);
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
        $solicitud->addPago($pago);
        $this->entityManager->persist($pago);
        return $pago;
    }

    protected function tearDown()
    {
        $finder = new Finder();
        $files = $finder->files()->in(__DIR__ . '/../../../web/tests/uploads/files/instituciones/*');

        $fileSystem = new Filesystem();
        $fileSystem->remove($files);
    }
}
