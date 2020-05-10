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

    protected function setUp()
    {
        parent::setUp();

        $this->pagoRepositoryInterface = $this->container->get(PagoRepositoryInterface::class);
        $this->estatusCampoRepository = $this->container->get(EstatusCampoRepositoryInterface::class);
        $this->logger = $this->container->get('logger');

        $this->clearTablaPago();
        $this->clearTablaCampoClinico();
        $this->clearTablaSolicitud();
    }

    public function testUploadComprobanteSuccessfully()
    {
        $referenciaBancaria = 1000001;

        $convenio = $this->entityManager->getRepository(Convenio::class)
            ->find(1);

        $solicitud = new Solicitud();
        $solicitud->setMonto(10000);
        $solicitud->setReferenciaBancaria($referenciaBancaria);
        $solicitud->setNoSolicitud('00001');
        $solicitud->setEstatus(SolicitudInterface::CARGANDO_COMPROBANTES);
        $this->entityManager->persist($solicitud);

        $pago = new Pago();
        $pago->setMonto(10000);
        $pago->setReferenciaBancaria($referenciaBancaria);
        $pago->setRequiereFactura(false);
        $pago->setSolicitud($solicitud);
        $this->entityManager->persist($pago);

        $campoClinico = new CampoClinico();
        $campoClinico->setReferenciaBancaria($referenciaBancaria);
        $campoClinico->setMonto(10000);
        $campoClinico->setEstatus(
            $this->estatusCampoRepository->findOneBy(['nombre' => EstatusCampoInterface::PENDIENTE_DE_PAGO])
        );
        $campoClinico->setLugaresSolicitados(10);
        $campoClinico->setLugaresAutorizados(10);
        $campoClinico->setPromocion('promicion');
        $campoClinico->setHorario('10am a 5pm');
        $campoClinico->setFechaInicial(Carbon::now());
        $campoClinico->setFechaFinal(Carbon::now()->addMonths(3));
        $campoClinico->setSolicitud($solicitud);
        $campoClinico->setConvenio($convenio);
        $this->entityManager->persist($campoClinico);

        $this->entityManager->flush();

        $service = new UploaderComprobantePago(
            $this->entityManager,
            $this->pagoRepositoryInterface,
            $this->logger
        );

        $file = new File(__DIR__ . '/pdf-test.pdf');
        $uploadedFile = new UploadedFile(
            $file->getRealPath(),
            $file->getFilename(),
            $file->getMimeType(),
            $file->getSize(),
            null,
            true
        );

        $service->update(
            $campoClinico,
            $uploadedFile
        );
    }
}
