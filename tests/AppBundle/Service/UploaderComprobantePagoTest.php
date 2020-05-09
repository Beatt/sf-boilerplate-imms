<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Pago;
use AppBundle\Repository\EstatusCampoRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Service\UploaderComprobantePago;
use Carbon\Carbon;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\AclBundle\Entity\Car;
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

        $pago = new Pago();
        $pago->setMonto(10000);
        $pago->setReferenciaBancaria(1000001);
        $pago->setRequiereFactura(false);
        $this->pagoRepositoryInterface->save($pago);

        $campoClinico = new CampoClinico();
        $campoClinico->setReferenciaBancaria($referenciaBancaria);
        $campoClinico->setMonto(10000);
        $campoClinico->setEstatus(
            $this->estatusCampoRepository->find(EstatusCampoInterface::PENDIENTE_DE_PAGO)
        );
        $campoClinico->setLugaresSolicitados(10);
        $campoClinico->setLugaresAutorizados(10);
        $campoClinico->setPromocion('promicion');
        $campoClinico->setHorario('10am a 5pm');
        $campoClinico->setFechaInicial(Carbon::now());
        $campoClinico->setFechaFinal(Carbon::now()->addMonths(3));

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
}
