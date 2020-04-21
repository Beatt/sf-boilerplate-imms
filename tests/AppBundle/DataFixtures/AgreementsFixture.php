<?php

namespace Tests\AppBundle\DataFixtures;

use AppBundle\Entity\Convenio;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AgreementsFixture extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // MAYOR A UN AÑO
        for ($i = 0; $i < 3; $i++) {
            $convenio = new Convenio();
            $convenio->setTipo('Tipo a');
            $convenio->setVigencia(Carbon::now()->addMonths(14));
            $convenio->setSector('Sector a');
            $manager->persist($convenio);
        }

        // MENOR A UN AÑO Y MAYOR A SEIS MESES
        for ($i = 0; $i < 2; $i++) {
            $convenio = new Convenio();
            $convenio->setTipo('Tipo a');
            $convenio->setVigencia(Carbon::now()->addMonths(7));
            $convenio->setSector('Sector a');
            $manager->persist($convenio);
        }

        for ($i = 0; $i < 1; $i++) {
            $convenio = new Convenio();
            $convenio->setTipo('Tipo a');
            $convenio->setVigencia(Carbon::now()->subMonths(4));
            $convenio->setSector('Sector a');
            $manager->persist($convenio);
        }

        $manager->flush();
    }
}
