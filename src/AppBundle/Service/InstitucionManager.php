<?php

namespace AppBundle\Service;

use AppBundle\Entity\Institucion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;

class InstitucionManager implements InstitucionManagerInterface
{
    private $entityManager;

    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function Create(Institucion $institucion)
    {
        $this->entityManager->persist($institucion);

        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
            return false;
        }

        return true;
    }
}
