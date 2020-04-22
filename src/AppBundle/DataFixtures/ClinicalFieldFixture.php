<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\CampoClinico;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ClinicalFieldFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $this->create(
            AgreementsFixture::AGREEMENT_GREATER_THAN_ONE_YEAR,
            CicloAcademicoFixture::CICLO_A,
            $manager
        );

        $this->create(
            AgreementsFixture::AGREEMENT_LESS_THAN_ONE_YEAR_AND_GREATER_THAN_SIX_MONTHS,
            CicloAcademicoFixture::CICLO_B,
            $manager
        );
    }

    function getDependencies()
    {
        return[
            AgreementsFixture::class,
            CicloAcademicoFixture::class
        ];
    }

    private function create(
        $convenioReference,
        $cicloAcademicoReference,
        ObjectManager $manager
    ) {
        $campoClinico = new CampoClinico();
        $campoClinico->setConvenio($this->getReference($convenioReference));
        $campoClinico->setCicloAcademico($this->getReference($cicloAcademicoReference));
        $campoClinico->setFechaInicial(Carbon::now());
        $campoClinico->setFechaFinal(Carbon::now()->addMonths(8));
        $campoClinico->setHorario('10am a 14:00pm');
        $campoClinico->setPromocion('promocion');
        $campoClinico->setLugaresSolicitados(40);
        $campoClinico->setLugaresAutorizados(20);
        $campoClinico->setReferenciaBancaria('102012010210');
        $campoClinico->setMonto('100000');

        $manager->persist($campoClinico);
        $manager->flush();
    }
}
