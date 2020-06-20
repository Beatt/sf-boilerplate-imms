<?php

namespace Tests\AppBundle\Normalizer;

use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use Tests\AppBundle\AbstractWebTestCase;

class GestionPagoNormalizerTest extends AbstractWebTestCase
{
    /**
     * @var PagoRepositoryInterface
     */
    private $pagoRepository;

    private $normalizer;

    protected function setUp()
    {
        parent::setUp();
        $this->pagoRepository = $this->container->get(PagoRepositoryInterface::class);
        $this->normalizer = $this->container->get('serializer');
    }

    public function testSome()
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this
            ->entityManager
            ->getRepository(Solicitud::class)
            ->findOneBy(['estatus' => SolicitudInterface::CARGANDO_COMPROBANTES])
        ;

        $result = $this->normalizer->normalize($solicitud->getGestionPago(), 'json', [
            'attributes' => [
                'pagos' => [
                    'comprobanteConEnlace',
                    'referenciaBancaria',
                    'fechaPago',
                    'monto'
                ],
                'ultimoPago' => [
                    'observaciones'
                ],
                'montoTotal',
                'montoTotalPorPagar'
            ]
        ]);

        dump($result);

        /*dump($this->normalizer->normalize($solicitud, 'json', [
            'attributes' => [
                'pagos' => [
                    'referenciaBancaria',
                    'comprobanteConEnlace',
                    'fechaPago',
                    'monto'
                ],
                'ultimoPago' => [
                    'observaciones',
                ],
                'montoTotalPagar'
            ]
        ]));*/
    }
}
