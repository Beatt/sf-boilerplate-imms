<?php

namespace AppBundle\Normalizer;

use AppBundle\Entity\Institucion;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CampoClinicoNormalizer implements InstitucionPerfilNormalizerInterface
{
    private $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalizeCamposClinicos(array $camposClinicos)
    {
        return $this->normalizer->normalize(
            $camposClinicos,
            'json',
            [
                'attributes' => [
                    'id',
                    'cicloAcademico' => [
                        'nombre'
                    ],
                    'convenio' => [
                        'id',
                        'vigencia',
                        'label',
                        'carrera' => [
                            'nombre',
                            'nivelAcademico' => [
                                'nombre'
                            ]
                        ],
                        'cicloAcademico' => [
                            'nombre'
                        ]
                    ]
                ]
            ]
        );
    }

    public function normalizeInstitucion(Institucion $institucion)
    {
        return $this->normalizer->normalize(
            $institucion,
            'json',
            [
                'attributes' => [
                    'id',
                    'nombre',
                    'rfc',
                    'direccion',
                    'correo',
                    'telefono',
                    'fax',
                    'sitioWeb',
                    'cedulaIdentificacion'
                ]
            ]
        );
    }
}
