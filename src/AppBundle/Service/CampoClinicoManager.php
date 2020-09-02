<?php


namespace AppBundle\Service;


use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
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
        $estatus = $this->entityManager->getRepository(EstatusCampo::class)->find(1);
        $campoClinico->setEstatus($estatus);
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
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        return [
            'status' => true,
            'message' => 'Campo clínico registrado con éxito',
            'object' => $serializer->normalize($campoClinico, 'json', [
                'attributes' =>[
                    'id',
                    'periodo',
                    'unidad' => ['id', 'nombre'],
                    'lugaresAutorizados',
                    'convenio' => ['id',
                        'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']],
                        'cicloAcademico' => ['id', 'nombre'],
                    ],
                    'lugaresSolicitados',
                    'fechaInicial', 'fechaFinal','fechaInicialFormatted', 'fechaFinalFormatted',
                    'horario', 'promocion', 'asignatura'
                ]
            ])
        ];
    }

    public function delete(CampoClinico $campoClinico)
    {
        try{
            $this->entityManager->remove($campoClinico);
            $this->entityManager->flush();

        }catch (\Exception $ex) {
            return [
                'status' => false,
                'error' => $ex->getMessage()
            ];
        }
        return [
            'status' => true
        ];
    }
}