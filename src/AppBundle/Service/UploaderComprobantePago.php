<?php

namespace AppBundle\Service;

use AppBundle\Entity\ComprobantePagoInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

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

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        PagoRepositoryInterface $pagoRepository,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->pagoRepository = $pagoRepository;
        $this->logger = $logger;
    }

    /**
     * @param ComprobantePagoInterface $pago
     * @return bool
     * @throws Exception
     */
    public function update(ComprobantePagoInterface $pago)
    {
        $this->logger->info(sprintf(
            'Iniciado el guardado del comprobante de pago para el pago con id %s', $pago->getId()
        ));

        try {
            $this->entityManager->flush();
        } catch (Exception $exception) {
            $this->logger->critical($exception->getMessage());
            throw $exception;
        }

        $this->logger->info('El comprobante de pago se ha guardado correctamente');

        return true;
    }
}
