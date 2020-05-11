<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Convenio;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\Unidad;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CampoClinicoFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
<<<<<<< HEAD
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
=======
        /** @var Convenio $convenio1 */
        $convenio1 = $manager->getRepository(Convenio::class)
            ->find(1);

        /** @var EstatusCampo $estatusCampoNuevo */
        $estatusCampoNuevo = $manager->getRepository(EstatusCampo::class)
            ->findOneBy(['nombre' => EstatusCampoInterface::NUEVO]);

        /** @var Unidad $unidad */
        $unidad = $manager->getRepository(Unidad::class)
            ->find(1);

        /** @var Solicitud $solicitud */
        $solicitud = $this->getReference(Solicitud::CONFIRMADA);

        $campo1 = $this->create(
            $convenio1,
            $estatusCampoNuevo,
            $unidad,
            $solicitud
        );
        $manager->persist($campo1);

        /** @var Convenio $convenio2 */
        $convenio2 = $manager->getRepository(Convenio::class)
            ->find(4);

        /** @var EstatusCampo $estatusCampoNuevo */
        $estatusCampoNuevo = $manager->getRepository(EstatusCampo::class)
            ->findOneBy(['nombre' => EstatusCampoInterface::PENDIENTE_DE_PAGO]);

        /** @var Unidad $unidad2 */
        $unidad2 = $manager->getRepository(Unidad::class)
            ->find(4);

        /** @var Solicitud $solicitudFormatos */
        $solicitudFormatos = $this->getReference(Solicitud::FORMATOS_DE_PAGO_GENERADOS);

        $campo2 = $this->create(
            $convenio2,
            $estatusCampoNuevo,
            $unidad2,
            $solicitudFormatos
        );
        $manager->persist($campo2);

        /** @var Convenio $convenio3 */
        $convenio3 = $manager->getRepository(Convenio::class)
            ->find(14);

        /** @var EstatusCampo $estatusCampoNuevo */
        $estatusCampoNuevo = $manager->getRepository(EstatusCampo::class)
            ->findOneBy(['nombre' => EstatusCampoInterface::PENDIENTE_DE_PAGO]);

        /** @var Unidad $unidad3 */
        $unidad3 = $manager->getRepository(Unidad::class)
            ->find(16);

        /** @var Solicitud $solicitudFormatos */
        $solicitudFormatos = $this->getReference(Solicitud::CARGANDO_COMPROBANTES);

        $campo3 = $this->create(
            $convenio3,
            $estatusCampoNuevo,
            $unidad3,
            $solicitudFormatos,
            '101011'
        );
        $manager->persist($campo3);
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012

        //$manager->flush();
    }

    function getDependencies()
    {
        return[
            SolicitudFixture::class
        ];
    }

    /**
<<<<<<< HEAD
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
=======
     * @param Convenio $convenio
     * @param EstatusCampo $estatusCampo
     * @param Unidad $unidad
     * @param Solicitud $solicitud
     * @param string|null $referenciaBancaria
     * @return CampoClinico
     */
    private function create(
        Convenio $convenio,
        EstatusCampo $estatusCampo,
        Unidad $unidad,
        $solicitud,
        $referenciaBancaria = null
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012
    ) {
        $campoClinico = new CampoClinico();
        $campoClinico->setFechaInicial(Carbon::now());
        $campoClinico->setFechaFinal(Carbon::now()->addMonths(8));
        $campoClinico->setHorario('10am a 14:00pm');
        $campoClinico->setPromocion('Promoción');
        $campoClinico->setLugaresSolicitados(40);
        $campoClinico->setLugaresAutorizados(20);
<<<<<<< HEAD
        $campoClinico->setEstatus($this->getReference($estatusCampoReference));
        $campoClinico->setSolicitud($this->getReference($solicitudCampoReferece));

        $manager->persist($campoClinico);
        $manager->flush();
=======
        $campoClinico->setMonto(10000);
        $campoClinico->setReferenciaBancaria($referenciaBancaria);
        $campoClinico->setConvenio($convenio);
        $campoClinico->setEstatus($estatusCampo);
        $campoClinico->setUnidad($unidad);
        $campoClinico->setSolicitud($solicitud);
>>>>>>> 60a9bc8534ada9854bb9777b893e7609e9856012

        return $campoClinico;
    }
}
