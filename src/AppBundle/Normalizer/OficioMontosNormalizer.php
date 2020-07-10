<?php

namespace AppBundle\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class OficioMontosNormalizer implements NormalizerInterface
{
    private $normalizer;

    private $institucionDocumentsDir;

    public function __construct(ObjectNormalizer $normalizer, $institucionDocumentsDir)
    {
        $this->normalizer = $normalizer;
        $this->institucionDocumentsDir = $institucionDocumentsDir;
    }

    /**
     * @param OficioMontosFileInterfaces $oficioMontosFile
     * @param string $format
     * @param array $context
     * @return array
     */
    public function normalize($oficioMontosFile, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($oficioMontosFile, $format, $context);

        if(empty($data['urlArchivo'])) return $data;

        $data['urlArchivo'] = sprintf('%s/%s',
            $this->institucionDocumentsDir,
            $data['urlArchivo']
        );
        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof OficioMontosFileInterfaces;
    }
}
