<?php

namespace AppBundle\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ComprobantePagoNormalizer implements NormalizerInterface
{
    private $normalizer;

    private $institucionDocumentsDir;

    public function __construct(ObjectNormalizer $normalizer, $institucionDocumentsDir)
    {
        $this->normalizer = $normalizer;
        $this->institucionDocumentsDir = $institucionDocumentsDir;
    }

    /**
     * @param ComprobantePagoFileInterface $comprobantePagoFile
     * @param string $format
     * @param array $context
     * @return array
     */
    public function normalize($comprobantePagoFile, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($comprobantePagoFile, $format, $context);

        if(empty($data['urlArchivo'])) return $data;

        $data['urlArchivo'] = sprintf('%s/%s',
            $this->institucionDocumentsDir,
            $data['urlArchivo']
        );
        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ComprobantePagoFileInterface;
    }
}
