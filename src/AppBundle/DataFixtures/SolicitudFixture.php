<?php

namespace AppBundle\DataFixtures;

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
        $solicitudConfirmada = $this->createSolicitudConfirmada($manager);
        $solicitudFormatoPago = $this->createSolicitudFormatosDePagoGenerados($manager);
        $solicitudCargandoComprobantes = $this->createSolicitudCargandoComprobantes($manager);

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
        $estatus,
        $fecha,
        $tipoPago = null,
        $referenciaBancaria = null
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

    /**
     * @param ObjectManager $manager
     * @return Solicitud
     */
    protected function createSolicitudConfirmada(ObjectManager $manager)
    {
        $solicitudConfirmada = $this->create(
            SolicitudInterface::CONFIRMADA,
            Carbon::now()->addMonths(3)
        );
        $manager->persist($solicitudConfirmada);
        return $solicitudConfirmada;
    }

    /**
     * @param ObjectManager $manager
     * @return Solicitud
     */
    protected function createSolicitudFormatosDePagoGenerados(ObjectManager $manager)
    {
        $solicitudFormatoPago = $this->create(
            SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS,
            Carbon::now()->addMonths(6)
        );
        $manager->persist($solicitudFormatoPago);
        return $solicitudFormatoPago;
    }

    /**
     * @param ObjectManager $manager
     * @return Solicitud
     */
    protected function createSolicitudCargandoComprobantes(ObjectManager $manager)
    {
        $solicitudCargandoComprobantes = $this->create(
            SolicitudInterface::CARGANDO_COMPROBANTES,
            Carbon::now()->addMonths(5),
            SolicitudTipoPagoInterface::TIPO_PAGO_MULTIPLE,
            '100003'
        );
        $manager->persist($solicitudCargandoComprobantes);
        return $solicitudCargandoComprobantes;
    }
}
