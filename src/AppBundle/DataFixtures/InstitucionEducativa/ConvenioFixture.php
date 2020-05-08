<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\Convenio;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ConvenioFixture extends Fixture implements FixtureInterface, DependentFixtureInterface
{
    const AGREEMENT_GREATER_THAN_ONE_YEAR = 'agreement_greater_than_one_year';
    const AGREEMENT_LESS_THAN_ONE_YEAR_AND_GREATER_THAN_SIX_MONTHS = 'agreement_less_than_one_year_and_greater_than_six_months';
    const AGREEMENT_LESS_THAN_SIX_MONTHS = 'agreement_less_than_six_months';

    public function load(ObjectManager $manager)
    {
        // MAYOR A UN AÑO
        for ($i = 0; $i < 1; $i++) {
            $convenio = new Convenio();
            $convenio->setNombre('Convenio a');
            $convenio->setTipo('Tipo a');
            $convenio->setVigencia(Carbon::now()->addMonths(14));
            $convenio->setSector('Sector a');
            $convenio->setCarrera($this->getReference(CarreraFixture::CARRERA_A));
            $convenio->setInstitucion($this->getReference(InstitucionFixture::INSTITUCION_A));
            $convenio->setCicloAcademico($this->getReference(CicloAcademicoFixture::CICLO_A));
            $convenio->setDelegacion($this->getReference(DelegacionFixture::DELEGACION_A));

            $manager->persist($convenio);

            $this->addReference(self::AGREEMENT_GREATER_THAN_ONE_YEAR, $convenio);
        }

        // MENOR A UN AÑO Y MAYOR A SEIS MESES
        for ($i = 0; $i < 1; $i++) {
            $convenio = new Convenio();
            $convenio->setNombre('Convenio b');
            $convenio->setTipo('Tipo a');
            $convenio->setVigencia(Carbon::now()->addMonths(7));
            $convenio->setSector('Sector b');
            $convenio->setCarrera($this->getReference(CarreraFixture::CARRERA_A));
            $convenio->setInstitucion($this->getReference(InstitucionFixture::INSTITUCION_A));
            $convenio->setCicloAcademico($this->getReference(CicloAcademicoFixture::CICLO_B));
            $convenio->setDelegacion($this->getReference(DelegacionFixture::DELEGACION_B));

            $manager->persist($convenio);

            $this->addReference(self::AGREEMENT_LESS_THAN_ONE_YEAR_AND_GREATER_THAN_SIX_MONTHS, $convenio);
        }

        for ($i = 0; $i < 1; $i++) {
            $convenio = new Convenio();
            $convenio->setNombre('Convenio c');
            $convenio->setTipo('Tipo a');
            $convenio->setVigencia(Carbon::now()->subMonths(4));
            $convenio->setSector('Sector c');
            $convenio->setCarrera($this->getReference(CarreraFixture::CARRERA_A));
            $convenio->setInstitucion($this->getReference(InstitucionFixture::INSTITUCION_A));
            $convenio->setCicloAcademico($this->getReference(CicloAcademicoFixture::CICLO_C));
            $convenio->setDelegacion($this->getReference(DelegacionFixture::DELEGACION_A));

            $manager->persist($convenio);

            $this->addReference(self::AGREEMENT_LESS_THAN_SIX_MONTHS, $convenio);
        }

        $manager->flush();
    }

    function getDependencies()
    {
        return[
            CarreraFixture::class,
            NivelAcademicoFixture::class,
            DelegacionFixture::class
        ];
    }
}
