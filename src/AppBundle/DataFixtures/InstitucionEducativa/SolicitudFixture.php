<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SolicitudFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $solicitudConfirmada = $this->create(
            '1000001',
            SolicitudInterface::CONFIRMADA,
            Carbon::now()->addMonths(3)
        );
        $manager->persist($solicitudConfirmada);

        $solicitudFormatoPago = $this->create(
            '1000002',
            SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS,
            Carbon::now()->addMonths(6)
        );
        $manager->persist($solicitudFormatoPago);

        $solicitudCargandoComprobantes = $this->create(
            '100003',
            SolicitudInterface::CARGANDO_COMPROBANTES,
            Carbon::now()->addMonths(5),
            SolicitudTipoPagoInterface::TIPO_PAGO_MULTIPLE
        );
        $manager->persist($solicitudCargandoComprobantes);

        $manager->flush();

        $this->addReference(
            SolicitudInterface::CONFIRMADA,
            $solicitudConfirmada
        );

        $this->addReference(
            SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS,
            $solicitudFormatoPago
        );

        $this->addReference(
          SolicitudInterface::CARGANDO_COMPROBANTES,
          $solicitudCargandoComprobantes
        );
    }

    private function create(
        $referenciaBancaria,
        $estatus,
        $fecha,
        $tipoPago = null
    ) {
        $tipoPago = $tipoPago ?: SolicitudTipoPagoInterface::TIPO_PAGO_UNICO;

        $solicitud = new Solicitud();
        $solicitud->setEstatus($estatus);
        $solicitud->setNoSolicitud(sprintf('NS_00%s', rand(0, 10000)));
        $solicitud->setFecha($fecha);
        $solicitud->setReferenciaBancaria($referenciaBancaria);
        $solicitud->setTipoPago($tipoPago);

        return $solicitud;
    }
}
