<?php

namespace AppBundle\Normalizer;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormaPagoNormalizer implements FormaPagoNormalizerInterface
{
    const JSON_FORMAT_TYPE = 'json';

    private $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalizeCamposClinicos(Collection $camposClinicos)
    {
        return $this->normalizer->normalize(
            $camposClinicos,
            self::JSON_FORMAT_TYPE,
            [
                'attributes' => [
                    'id',
                    'unidad' => [
                        'nombre'
                    ],
                    'convenio' => [
                        'carrera' => [
                            'nombre',
                            'nivelAcademico' => [
                                'nombre'
                            ]
                        ],
                        'cicloAcademico' => [
                            'nombre'
                        ]
                    ],
                    'lugaresSolicitados',
                    'lugaresAutorizados',
                    'fechaInicial',
                    'fechaFinal',
                    'numeroSemanas',
                    'montoPagar',
                    'enlaceCalculoCuotas'
                ]
            ]
        );
    }
}
