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

        $campoClinico = $this->create(
            ConvenioFixture::AGREEMENT_GREATER_THAN_ONE_YEAR,
            CicloAcademicoFixture::CICLO_A,
            EstatusCampo::MONTOS_VALIDADOS,
            $manager
        );

        $campoClinico1 = $this->create(
            ConvenioFixture::AGREEMENT_GREATER_THAN_ONE_YEAR,
            CicloAcademicoFixture::CICLO_A,
            EstatusCampo::MONTOS_VALIDADOS,
            $manager
        );
        $campoClinico2 = $this->create(
            ConvenioFixture::AGREEMENT_GREATER_THAN_ONE_YEAR,
            CicloAcademicoFixture::CICLO_A,
            EstatusCampo::EN_VALIDACION_POR_FOFOE,
            $manager
        );

        $campoClinico1->setSolicitud($this->getReference(Solicitud::CONFIRMADA));
        $campoClinico2->setSolicitud($this->getReference(Solicitud::EN_VALIDACION_DE_MONTOS));

        $manager->flush();
    }

    function getDependencies()
    {
        return[
            ConvenioFixture::class,
            CicloAcademicoFixture::class
        ];
    }

    /**
     * @param $convenioReference
     * @param $cicloAcademicoReference
     * @param $estatusCampoReference
     * @param ObjectManager $manager
     * @return CampoClinico
     */
    private function create(
        $convenioReference,
        $cicloAcademicoReference,
        $estatusCampoReference,
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
        $campoClinico->setEstatus($this->getReference($estatusCampoReference));

        $manager->persist($campoClinico);

        return $campoClinico;
    }
}
