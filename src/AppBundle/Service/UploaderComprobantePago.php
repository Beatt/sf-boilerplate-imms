<?php

namespace AppBundle\Service;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Repository\PagoRepositoryInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderComprobantePago implements UploaderComprobantePagoInterface
{
    /**
     * @var PagoRepositoryInterface
     */
    private $pagoRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(PagoRepositoryInterface $pagoRepository, LoggerInterface $logger)
    {
        $this->pagoRepository = $pagoRepository;
        $this->logger = $logger;
    }

    /**
     * @param CampoClinico $campoClinico
     * @param UploadedFile $file
     * @throws Exception
     */
    public function update(CampoClinico $campoClinico, UploadedFile $file)
    {
        /** @var Pago $pago */
        $pago = $this->pagoRepository->getComprobante($campoClinico->getReferenciaBancaria());
        if($pago === null) throw new Exception('El campo clinico no tiene un pago asociado');

        $this->logger->info(sprintf(
            'Iniciado el guardado del comprobante de pago del campo clinico con id %s', $campoClinico->getId()
        ));

        try {
            $this->logger->info('Subiendo el comprobante de pago');
            $pago->setComprobantePagoFile($file);
            $this->pagoRepository->save($pago);
        } catch (Exception $exception) {
            $this->logger->critical($exception->getMessage());
            throw $exception;
        }

        $this->logger->info('El comprobante de pago se ha guardado correctamente');

        return true;
    }
}
