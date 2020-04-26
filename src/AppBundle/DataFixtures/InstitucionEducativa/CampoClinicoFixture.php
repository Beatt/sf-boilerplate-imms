<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\CampoClinico;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CampoClinicoFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $this->create(
            ConvenioFixture::AGREEMENT_GREATER_THAN_ONE_YEAR,
            CicloAcademicoFixture::CICLO_A,
            $manager
        );

        $this->create(
            ConvenioFixture::AGREEMENT_LESS_THAN_ONE_YEAR_AND_GREATER_THAN_SIX_MONTHS,
            CicloAcademicoFixture::CICLO_B,
            $manager
        );
    }

    function getDependencies()
    {
        return[
            ConvenioFixture::class,
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

        $campoClinico->setSolicitud($this->getReference(SolicitudFixture::NUEVA));

        $manager->persist($campoClinico);
        $manager->flush();
    }
}
