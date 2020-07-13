<?php

namespace AppBundle\Service;

use AppBundle\Entity\Pago;
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
     * @param Pago $pago
     * @return bool
     * @throws Exception
     */
    public function update(Pago $pago)
    {
        $this->logger->info(sprintf(
            'Iniciado el guardado del comprobante de pago para el pago con id %s', $pago->getId()
        ));

        $file = $pago->getComprobantePagoFile();
        $pago->setComprobantePagoFile(null);
        $this->entityManager->flush();

        $pago->setComprobantePagoFile($file);
        $this->entityManager->flush();

        return true;
    }
}
