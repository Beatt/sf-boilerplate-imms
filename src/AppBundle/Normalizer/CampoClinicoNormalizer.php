<?php

namespace AppBundle\Normalizer;

use AppBundle\DTO\IE\PerfilInstitucionDTO;
use AppBundle\Entity\Institucion;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CampoClinicoNormalizer implements InstitucionPerfilNormalizerInterface
{
    private $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalizeConvenios(array $camposClinicos)
    {
        return $this->normalizer->normalize(
            $camposClinicos,
            'json',
            [
                'attributes' => [
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
        );
    }

    public function normalizeInstitucion(Institucion $institucion)
    {
        return $this->normalizer->normalize(
            new PerfilInstitucionDTO($institucion),
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
                    'cedulaIdentificacion',
                    'representante',
                    'confirmacionInformacion'
                ]
            ]
        );
    }
}
