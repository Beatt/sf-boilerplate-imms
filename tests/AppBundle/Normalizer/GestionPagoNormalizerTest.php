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
        $solicitud = $this->getSolicitudByEstatusCargandoComprobantes();

        $campoClinico = $this->getCampoClinico($solicitud);
        $campoClinico->setMonto($amount);

        $pago1 = $this->createPago($amount / 2, $solicitud, $campoClinico);
        $pago2 = $this->createPago($amount / 2, $solicitud, $campoClinico);

        $solicitud->addPago($pago1);
        $solicitud->addPago($pago2);

        $this->entityManager->flush();

        $result = $this->normalizer->normalize(
            $pago2->getGestionPago(),
            'json',
            $this->getAttributesToNormalize()
        );

        $this->assertEquals('20000', $result['montoTotal']);
        $this->assertEquals('0', $result['montoTotalPorPagar']);
        $this->assertNotEmpty($result['noSolicitud']);
        $this->assertNotEmpty($result['tipoPago']);
        $this->assertNotEmpty($result['campoClinico']['sede']);
        $this->assertNotEmpty($result['campoClinico']['carrera']);
        $this->assertNull($result['ultimoPago']['observaciones']);
        $this->assertCount(2, $result['pagos']);
    }

    public function testCampoClinicoParcialmentePagado()
    {
        $amount = 20000;

        $solicitud = $this->getSolicitudByEstatusCargandoComprobantes();

        $campoClinico = $this->getCampoClinico($solicitud);
        $campoClinico->setMonto($amount);

        $pago1 = $this->createPago($amount / 2, $solicitud, $campoClinico, true);
        $pago2 = $this->createPago($amount / 2, $solicitud, $campoClinico, false);

        $solicitud->addPago($pago1);
        $solicitud->addPago($pago2);

        $this->entityManager->flush();

        $result = $this->normalizer->normalize(
            $pago2->getGestionPago(),
            'json',
            $this->getAttributesToNormalize()
        );;

        $this->assertNotEmpty($result['noSolicitud']);
        $this->assertNotEmpty($result['tipoPago']);
        $this->assertNotEmpty($result['campoClinico']['sede']);
        $this->assertNotEmpty($result['campoClinico']['carrera']);
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
        $pago->setMonto($amount);
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

    /**
     * @return Solicitud
     */
    protected function getSolicitudByEstatusCargandoComprobantes()
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this
            ->entityManager
            ->getRepository(Solicitud::class)
            ->findOneBy(['estatus' => SolicitudInterface::CARGANDO_COMPROBANTES]);

        return $solicitud;
    }

    /**
     * @return array[]
     */
    protected function getAttributesToNormalize()
    {
        return [
            'attributes' => [
                'noSolicitud',
                'montoTotal',
                'montoTotalPorPagar',
                'tipoPago',
                'campoClinico' => [
                    'sede',
                    'carrera'
                ],
                'pagos' => [
                    'comprobanteConEnlace',
                    'referenciaBancaria',
                    'fechaPago',
                    'monto'
                ],
                'ultimoPago' => [
                    'observaciones'
                ]
            ]
        ];
    }

    /**
     * @param Solicitud $solicitud
     * @return CampoClinico
     */
    protected function getCampoClinico(Solicitud $solicitud)
    {
        return $solicitud->getCamposClinicos()->first();
    }
}
