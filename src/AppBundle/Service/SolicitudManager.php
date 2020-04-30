<?php


namespace AppBundle\Service;


use AppBundle\Entity\Solicitud;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
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
        } catch (OptimisticLockException $exception) {
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
        try {
            $this->entityManager->persist($solicitud);
            $this->entityManager->flush();
            $solicitud->setNoSolicitud("NS_" . str_pad($solicitud->getId(), 6, '0', STR_PAD_LEFT));
            $this->entityManager->persist($solicitud);
            $this->entityManager->flush();
        } catch (OptimisticLockException $exception) {
            $this->logger->critical($exception->getMessage());
            return [
                'status' => false,
                'error' => $exception->getMessage()
            ];
        }
        return [
            'status' => true,
            'object' => ['id' => $solicitud->getId(), 'fecha' => $solicitud->getFecha(), 'no_solicitud' => $solicitud->getNoSolicitud()]
        ];
    }

    public function finalizar(Solicitud $solicitud)
    {

    }

    public function validarMontos(Solicitud $solicitud, Request $request)
    {

    }
}