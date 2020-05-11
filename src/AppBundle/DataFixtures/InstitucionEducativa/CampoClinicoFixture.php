<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CampoClinicoFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $campoClinico1 = $this->create(
            ConvenioFixture::AGREEMENT_GREATER_THAN_ONE_YEAR,
            EstatusCampo::NUEVO,
            Solicitud::CONFIRMADA,
            $manager
        );
        //$campoClinico1->setSolicitud($this->getReference(Solicitud::CONFIRMADA));

        $campoClinico2 = $this->create(
            ConvenioFixture::AGREEMENT_GREATER_THAN_ONE_YEAR,
            EstatusCampo::NUEVO,
            SolicitudInterface::EN_VALIDACION_DE_MONTOS_CAME,
            $manager
        );
        //$campoClinico2->setSolicitud($this->getReference(SolicitudInterface::EN_VALIDACION_DE_MONTOS_CAME));

        $campoClinico3 = $this->create(
            ConvenioFixture::AGREEMENT_GREATER_THAN_ONE_YEAR,
            EstatusCampo::PENDIENTE_DE_PAGO,
            SolicitudInterface::CARGANDO_COMPROBANTES,
            $manager
        );
        //$campoClinico3->setSolicitud($this->getReference(SolicitudInterface::CARGANDO_COMPROBANTES));

        //$manager->flush();
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
     * @param $estatusCampoReference
     * @param $solicitudCampoReferece
     * @param ObjectManager $manager
     * @return CampoClinico
     */
    private function create(
        $convenioReference,
        $estatusCampoReference,
        $solicitudCampoReferece,
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
        $campoClinico->setSolicitud($this->getReference($solicitudCampoReferece));

        $manager->persist($campoClinico);
        $manager->flush();

        return $campoClinico;
    }
}
