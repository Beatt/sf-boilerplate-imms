<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\Solicitud;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SolicitudFixture extends Fixture
{
    const NUEVA = 'nueva';
    const EN_ESPERA_DE_VALIDACION_DE_MONTOS = 'en_espera_de_validacion_de_montos';
    const MONTOS_VALIDADOS = 'montos_validados';
    const PAGO_EN_PROCESO = 'pago_en_proceso';
    const PAGADO = 'pagado';
    const EN_VALIDACION_POR_FOFOE = 'en_validacion_por_fofoe';

    public function load(ObjectManager $manager)
    {
        $this->Create(
            self::NUEVA,
            Carbon::now(),
            $manager
        );
        $this->Create(
            self::EN_ESPERA_DE_VALIDACION_DE_MONTOS,
            Carbon::now()->addMonths(4),
            $manager
        );
        $this->Create(
            self::MONTOS_VALIDADOS,
            Carbon::now()->addMonths(6),
            $manager
        );
        $this->Create(
            self::PAGO_EN_PROCESO,
            Carbon::now()->addMonths(8),
            $manager
        );
        $this->Create(
            self::PAGADO,
            Carbon::now()->addMonths(12),
            $manager
        );
        $this->Create(
            self::EN_VALIDACION_POR_FOFOE,
            Carbon::now()->addMonths(15),
            $manager
        );
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

        $manager->persist($solicitud);
        $manager->flush();

        $this->addReference($typeReference, $solicitud);
    }
}
