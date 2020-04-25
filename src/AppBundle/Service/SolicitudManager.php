<?php


namespace AppBundle\Service;


use AppBundle\Entity\Solicitud;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SolicitudManager implements SolicitudManagerInterface
{

    private $entityManager;

    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function update(Solicitud $solicitud)
    {
        $this->entityManager->persist($solicitud);
        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
            return [
                'status' => false,
                'error' => $exception->getMessage()
            ];
        }
        return [
            'status' => true
        ];
    }

    public function create(Solicitud $solicitud)
    {
        $solicitud->setEstatus(1);
        $solicitud->setFecha(Carbon::now());
        $this->entityManager->persist($solicitud);
        $solicitud->setNoSolicitud("NS_".str_pad($solicitud->getId(), 6, '0', STR_PAD_LEFT));
        try {
            $this->entityManager->flush();
        } catch(OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
            return [
                'status' => false,
                'error' => $exception->getMessage()
            ];
        }
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        return [
            'status' => true,
            'object' => ['id' => $solicitud->getId(), 'no_solicitud' => $solicitud->getNoSolicitud()]
        ];
    }
}