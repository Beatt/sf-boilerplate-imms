<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
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

        $manager->flush();

        $this->addReference(
            SolicitudInterface::CONFIRMADA,
            $solicitudConfirmada
        );

        $this->addReference(
            SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS,
            $solicitudFormatoPago
        );
    }

    private function create(
        $referenciaBancaria,
        $estatus,
        $fecha
    ) {
        $solicitud = new Solicitud();
        $solicitud->setEstatus($estatus);
        $solicitud->setNoSolicitud(sprintf('NS_00%s', rand(0, 10000)));
        $solicitud->setFecha($fecha);
        $solicitud->setReferenciaBancaria($referenciaBancaria);

        return $solicitud;
    }
}
