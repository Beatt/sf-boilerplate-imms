<?php

namespace AppBundle\Service;

use AppBundle\Entity\Institucion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class InstitucionManager implements InstitucionManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
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
            return  [
                'status' => false,
                'message' => 'Ocurrio un problema al guardar la institución',
            ];
        }

        $encoders = [new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        return [
            'status' => true,
            'object' => $serializer->normalize($institucion, 'json', ['attributes' =>[
                'id', 'nombre', 'rfc', 'direccion', 'telefono', 'correo', 'sitioWeb', 'fax', 'representante'
            ]]),
            'message' => 'Institucion almacenada con éxito',
        ];
    }
}
