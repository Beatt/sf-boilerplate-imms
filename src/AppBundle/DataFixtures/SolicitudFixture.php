<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SolicitudFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->createSolicitudConfirmada($manager);
        //$solicitudFormatoPago = $this->createSolicitudFormatosDePagoGenerados($manager);
        $this->createSolicitudCargandoComprobantes($manager);

        $manager->flush();
    }

    function getDependencies()
    {
        return[
            CampoClinicoFixture::class
        ];
    }

    private function create(
        $estatus,
        $fecha,
        $tipoPago = null,
        $referenciaBancaria = null
    ) {
        $solicitud = new Solicitud();
        $solicitud->setEstatus($estatus);
        $solicitud->setNoSolicitud(sprintf('NS_00%s', rand(0, 10000)));
        $solicitud->setFecha($fecha);
        $solicitud->setReferenciaBancaria($referenciaBancaria);
        $solicitud->setTipoPago($tipoPago);

        return $solicitud;
    }

    /**
     * @param ObjectManager $manager
     * @return Solicitud
     */
    protected function createSolicitudConfirmada(ObjectManager $manager)
    {
        $solicitud = $this->create(
            SolicitudInterface::CONFIRMADA,
            Carbon::now()->addMonths(3)
        );

        /** @var CampoClinico $campoClinicoNuevo */
        $campoClinicoNuevo = $this->getReference(EstatusCampoInterface::NUEVO);
        $solicitud->addCamposClinico($campoClinicoNuevo);

        $manager->persist($solicitud);
    }

    /**
     * @param ObjectManager $manager
     * @return Solicitud
     */
    protected function createSolicitudFormatosDePagoGenerados(ObjectManager $manager)
    {
        $solicitud = $this->create(
            SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS,
            Carbon::now()->addMonths(6)
        );

        /** @var CampoClinico $campoClinicoNuevo */
        $campoClinicoNuevo = $this->getReference(EstatusCampoInterface::NUEVO);
        $solicitud->addCamposClinico($campoClinicoNuevo);

        $manager->persist($solicitud);
    }

    /**
     * @param ObjectManager $manager
     * @return Solicitud
     */
    protected function createSolicitudCargandoComprobantes(ObjectManager $manager)
    {
        $solicitud = $this->create(
            SolicitudInterface::CARGANDO_COMPROBANTES,
            Carbon::now()->addMonths(5),
            SolicitudTipoPagoInterface::TIPO_PAGO_MULTIPLE
        );

        $monto = 20000;
        $referenciaBancaria = '1000001';
        /** @var CampoClinico $campoClinicoPendientePago */
        $campoClinicoPendientePago = $this->getReference(EstatusCampoInterface::PENDIENTE_DE_PAGO);
        $campoClinicoPendientePago->setMonto($monto);
        $campoClinicoPendientePago->setReferenciaBancaria($referenciaBancaria);

        $pago = new Pago();
        $pago->setFechaCreacion(Carbon::now());
        $pago->setReferenciaBancaria($referenciaBancaria);
        $pago->setRequiereFactura(true);
        $pago->setValidado(true);
        $pago->setMonto($monto / 2);
        $pago->setSolicitud($solicitud);
        $manager->persist($pago);

        $monto = 60000;
        $referenciaBancaria = '1000000';
        /** @var CampoClinico $campoClinicoPagado */
        $campoClinicoPagado = $this->getReference(EstatusCampoInterface::PAGO);
        $campoClinicoPagado->setReferenciaBancaria($referenciaBancaria);
        $campoClinicoPagado->setMonto($monto);

        $pago2 = $this->createPago($referenciaBancaria, $monto, $solicitud, $manager);
        $pago2->setFechaPago(Carbon::now()->subMonths(2));

        $pago3 = $this->createPago($referenciaBancaria, $monto, $solicitud, $manager);
        $pago3->setFechaPago(Carbon::now()->subMonths(1));

        $solicitud->addCamposClinico($campoClinicoPendientePago);
        $solicitud->addCamposClinico($campoClinicoPagado);
        $manager->persist($solicitud);

        $manager->flush();
    }

    /**
     * @param $referenciaBancaria
     * @param $monto
     * @param Solicitud $solicitud
     * @param ObjectManager $manager
     * @return Pago
     */
    protected function createPago($referenciaBancaria, $monto, Solicitud $solicitud, ObjectManager $manager)
    {
        $pago = new Pago();
        $pago->setFechaCreacion(Carbon::now());
        $pago->setReferenciaBancaria($referenciaBancaria);
        $pago->setRequiereFactura(true);
        $pago->setValidado(true);
        $pago->setMonto($monto / 3);
        $pago->setSolicitud($solicitud);
        $manager->persist($pago);
        return $pago;
    }
}
