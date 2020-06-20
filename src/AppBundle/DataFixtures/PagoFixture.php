<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Pago;
use AppBundle\Entity\SolicitudInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PagoFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $pago = new Pago();
        $pago->setSolicitud($this->getReference(SolicitudInterface::CARGANDO_COMPROBANTES));
        $pago->setRequiereFactura(false);
        $pago->setMonto(10000);
        $pago->setReferenciaBancaria('101011');

        $manager->persist($pago);
        $manager->flush();
    }

    function getDependencies()
    {
        return[
            SolicitudFixture::class
        ];
    }
}
