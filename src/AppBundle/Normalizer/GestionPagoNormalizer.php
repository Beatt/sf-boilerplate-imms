<?php

namespace AppBundle\Normalizer;

use AppBundle\DTO\GestionPagoDTO;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class GestionPagoNormalizer implements NormalizerInterface
{
    private $normalizer;

    private $comprobantesPagoDir;

    public function __construct(ObjectNormalizer $normalizer, $comprobantesPagoDir)
    {
        $this->normalizer = $normalizer;
        $this->comprobantesPagoDir = $comprobantesPagoDir;
    }

    /**
     * @param GestionPagoDTO $gestionPagoDTO
     * @param string $format
     * @param array $context
     * @return array
     */
    public function normalize($gestionPagoDTO, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($gestionPagoDTO, $format, $context);

        foreach($data['pagos'] as &$pago) {
            $pago['comprobanteConEnlace'] = sprintf('%s/%s/%s',
                $this->comprobantesPagoDir,
                $gestionPagoDTO->getNombreInstitucion(),
                $pago['comprobanteConEnlace']
            );
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof GestionPagoDTO;
    }
}
