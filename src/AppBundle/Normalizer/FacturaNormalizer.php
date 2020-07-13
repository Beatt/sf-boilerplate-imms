<?php

namespace AppBundle\Normalizer;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class FacturaNormalizer implements NormalizerInterface
{
    private $normalizer;

    private $institucionDocumentsDir;

    private $tokenStorage;

    public function __construct(
        ObjectNormalizer $normalizer,
        $institucionDocumentsDir,
        TokenStorageInterface $tokenStorage
    ) {
        $this->normalizer = $normalizer;
        $this->institucionDocumentsDir = $institucionDocumentsDir;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param FacturaFileInterface $facturaFile
     * @param string $format
     * @param array $context
     * @return array
     */
    public function normalize($facturaFile, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($facturaFile, $format, $context);

        if(empty($data['urlArchivo'])) return $data;

        $data['urlArchivo'] = sprintf('%s/%s/%s',
            $this->institucionDocumentsDir,
            $this->tokenStorage->getToken()->getUser()->getInstitucion()->getId(),
            $data['urlArchivo']
        );
        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FacturaFileInterface;
    }
}
