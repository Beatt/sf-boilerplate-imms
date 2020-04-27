<?php


namespace AppBundle\Service;


use AppBundle\Entity\CampoClinico;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CampoClinicoManager implements CampoClinicoManagerInterface
{

    private $entityManager;

    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }


    public function create(CampoClinico $campoClinico)
    {
        $campoClinico->setMonto(-1);
        $this->entityManager->persist($campoClinico);
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
            'object' => $serializer->normalize($campoClinico, 'json', [
                'attributes' =>[
                    'id',
                    'cicloAcademico' => ['id', 'nombre'],
                    'periodo',
                    'unidad' => ['id', 'nombre'],
                    'lugaresAceptados',
                    'lugaresSolicitados'
                ]
            ])
        ];
    }
}