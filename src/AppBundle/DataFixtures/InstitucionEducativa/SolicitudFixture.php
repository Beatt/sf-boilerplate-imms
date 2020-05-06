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
        $this->Create(
            SolicitudInterface::CONFIRMADA,
            Carbon::now(),
            $manager
        );

        $this->Create(
            SolicitudInterface::EN_VALIDACION_DE_MONTOS_CAME,
            Carbon::now(),
            $manager
        );

        $manager->flush();
    }

    private function Create(
        $typeReference,
        $fecha,
        ObjectManager $manager
    ) {
        $solicitud = new Solicitud();
        $solicitud->setEstatus($typeReference);
        $solicitud->setNoSolicitud(sprintf('NS_00%s', rand(0, 10000)));
        $solicitud->setFecha($fecha);
        $solicitud->setReferenciaBancaria('10202010220');
        $solicitud->setTipoPago(Solicitud::TIPO_PAGO_UNICO);

        $manager->persist($solicitud);
        $this->addReference($typeReference, $solicitud);

        return $solicitud;
    }
}
