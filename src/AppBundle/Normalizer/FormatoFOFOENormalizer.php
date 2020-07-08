<?php

namespace AppBundle\Normalizer;

use AppBundle\Repository\IE\SeleccionarFormaPago\ListaCamposClinicosAutorizados\CampoClinico;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class FormatoFOFOENormalizer implements NormalizerInterface
{
    private $normalizer;

    private $router;

    public function __construct(ObjectNormalizer $normalizer, RouterInterface $router)
    {
        $this->normalizer = $normalizer;
        $this->router = $router;
    }

    /**
     * @param CampoClinico $camposClinico
     * @param string $format
     * @param array $context
     * @return array
     */
    public function normalize($camposClinico, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($camposClinico, $format, $context);

        if($data['enlaceCalculoCuotas'] === '') return $data;

        $data['enlaceCalculoCuotas'] = $this->router->generate('campo_clinico.formato_fofoe.download', [
            'campo_clinico_id' => $camposClinico->getId()
        ]);

        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof CampoClinico;
    }
}
