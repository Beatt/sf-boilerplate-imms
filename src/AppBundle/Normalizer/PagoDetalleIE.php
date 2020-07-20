<?php

namespace AppBundle\Normalizer;

use AppBundle\DTO\IE\PerfilInstitucionDTO;
use AppBundle\Entity\Institucion;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PagoDetalleIE implements DetalleInstitucionNormalizerInterface
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
                    'monto',
                    'solicitud' => [
                        'noSolicitud',
                        'fecha'
                    ],
                    'fechaPago',
                    'factura' => [
                        'zip',
                        'requiereFactura'
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
                    'extension',
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
