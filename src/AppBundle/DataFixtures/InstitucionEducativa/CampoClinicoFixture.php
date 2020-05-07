<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\Solicitud;
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
            EstatusCampo::MONTOS_VALIDADOS,
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
        $estatusCampoReference,
        ObjectManager $manager
    ) {
        $campoClinico = new CampoClinico();
        $campoClinico->setConvenio($this->getReference($convenioReference));
        $campoClinico->setFechaInicial(Carbon::now());
        $campoClinico->setFechaFinal(Carbon::now()->addMonths(8));
        $campoClinico->setHorario('10am a 14:00pm');
        $campoClinico->setPromocion('promocion');
        $campoClinico->setLugaresSolicitados(40);
        $campoClinico->setLugaresAutorizados(20);
        $campoClinico->setEstatus($this->getReference($estatusCampoReference));

        $campoClinico->setSolicitud($this->getReference(Solicitud::CREADA));

        $manager->persist($campoClinico);
        $manager->flush();
    }
}
