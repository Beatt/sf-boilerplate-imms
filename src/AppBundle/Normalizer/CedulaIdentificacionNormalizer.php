<?php

namespace AppBundle\Normalizer;

use AppBundle\DTO\IE\PerfilInstitucionDTO;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CedulaIdentificacionNormalizer implements NormalizerInterface
{
    private $normalizer;

    private $cedulaIdentificacionDir;

    public function __construct(ObjectNormalizer $normalizer, $cedulaIdentificacionDir)
    {
        $this->normalizer = $normalizer;
        $this->cedulaIdentificacionDir = $cedulaIdentificacionDir;
    }

    /**
     * @param PerfilInstitucionDTO $perfilInstitucion
     * @param string $format
     * @param array $context
     * @return array
     */
    public function normalize($perfilInstitucion, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($perfilInstitucion, $format, $context);

        if($data['cedulaIdentificacion'] === null) return $data;

        $data['cedulaIdentificacion'] = sprintf('%s/%s',
            $this->cedulaIdentificacionDir . '/' . $perfilInstitucion->getNombre() ,
            $data['cedulaIdentificacion']
        );

        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof PerfilInstitucionDTO;
    }
}
