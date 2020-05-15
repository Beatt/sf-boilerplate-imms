<?php

namespace AppBundle\Service;

use AppBundle\Entity\MontoCarrera;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;

class MontoCarreraManager implements MontoCarreraManagerInterface
{
    private $entityManager;

    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function Create(MontoCarrera $montoCarrera)
    {
        $this->entityManager->persist($montoCarrera);

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
            return false;
        }

        return true;
    }
}
