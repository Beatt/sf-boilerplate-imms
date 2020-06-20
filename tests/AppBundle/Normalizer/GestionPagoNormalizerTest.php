<?php

namespace Tests\AppBundle\Normalizer;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
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
        $this->clearTablaPago();
    }

    public function testCampoClinicoPagado()
    {
        $amount = 20000;

        /** @var Solicitud $solicitud */
        $solicitud = $this
            ->entityManager
            ->getRepository(Solicitud::class)
            ->findOneBy(['estatus' => SolicitudInterface::CARGANDO_COMPROBANTES])
        ;

        /** @var CampoClinico $campoClinico */
        $campoClinico = $solicitud->getCamposClinicos()->first();
        $campoClinico->setMonto($amount);

        $pago1 = $this->createPago($amount, $solicitud, $campoClinico);
        $pago2 = $this->createPago($amount, $solicitud, $campoClinico);

        $solicitud->addPago($pago1);
        $solicitud->addPago($pago2);

        $this->entityManager->flush();

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


        $this->assertEquals('20000', $result['montoTotal']);
        $this->assertEquals('0', $result['montoTotalPorPagar']);
        $this->assertNull($result['ultimoPago']['observaciones']);
        $this->assertCount(2, $result['pagos']);
    }

    public function testCampoClinicoParcialmentePagado()
    {
        $amount = 20000;

        /** @var Solicitud $solicitud */
        $solicitud = $this
            ->entityManager
            ->getRepository(Solicitud::class)
            ->findOneBy(['estatus' => SolicitudInterface::CARGANDO_COMPROBANTES])
        ;

        /** @var CampoClinico $campoClinico */
        $campoClinico = $solicitud->getCamposClinicos()->first();
        $campoClinico->setMonto($amount);

        $pago1 = $this->createPago($amount, $solicitud, $campoClinico, true);
        $pago2 = $this->createPago($amount, $solicitud, $campoClinico, false);

        $solicitud->addPago($pago1);
        $solicitud->addPago($pago2);

        $this->entityManager->flush();

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


        $this->assertEquals('20000', $result['montoTotal']);
        $this->assertEquals('10000', $result['montoTotalPorPagar']);
        $this->assertNotNull($result['ultimoPago']['observaciones']);
        $this->assertCount(2, $result['pagos']);
    }


    /**
     * @param $amount
     * @param Solicitud $solicitud
     * @param CampoClinico $campoClinico
     * @param $isPagoValidado
     * @return Pago
     */
    protected function createPago(
        $amount,
        Solicitud $solicitud,
        CampoClinico $campoClinico,
        $isPagoValidado = true
    ) {
        $pago = new Pago();
        $pago->setMonto($amount / 2);
        $pago->setSolicitud($solicitud);
        $pago->setReferenciaBancaria($campoClinico->getReferenciaBancaria());
        $pago->setRequiereFactura(false);
        if(!$isPagoValidado) {
            $pago->setObservaciones('dummydata');
        }
        $pago->setValidado($isPagoValidado);
        $this->pagoRepository->save($pago);
        return $pago;
    }
}
