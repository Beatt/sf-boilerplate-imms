<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Convenio;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Unidad;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CampoClinicoFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /** @var Convenio $convenio */
        $convenio = $manager->getRepository(Convenio::class)
            ->find(3);

        /** @var EstatusCampo $estatusCampoNuevo */
        $estatusCampoNuevo = $manager->getRepository(EstatusCampo::class)
            ->findOneBy(['nombre' => EstatusCampoInterface::NUEVO]);

        /** @var Unidad $unidad */
        $unidad = $manager->getRepository(Unidad::class)
            ->find(1);

        $campoClinicoNuevo = $this->create(
            $convenio,
            $estatusCampoNuevo,
            $unidad
        );

        $campoClinicoPendientePago = $this->create(
            $convenio,
            $estatusCampoNuevo,
            $unidad
        );

        $campoClinicoPagado = $this->create(
            $convenio,
            $estatusCampoNuevo,
            $unidad
        );

        $manager->persist($campoClinicoNuevo);
        $manager->persist($campoClinicoPendientePago);
        $manager->persist($campoClinicoPagado);
        $manager->flush();

        $this->addReference(EstatusCampoInterface::NUEVO, $campoClinicoNuevo);
        $this->addReference(EstatusCampoInterface::PENDIENTE_DE_PAGO, $campoClinicoPendientePago);
        $this->addReference(EstatusCampoInterface::PAGO, $campoClinicoPagado);
    }

    /**
     * @param Convenio $convenio
     * @param EstatusCampo $estatusCampo
     * @param Unidad $unidad
     * @return CampoClinico
     */
    private function create(
        Convenio $convenio,
        EstatusCampo $estatusCampo,
        Unidad $unidad
    ) {
        $campoClinico = new CampoClinico();
        $campoClinico->setFechaInicial(Carbon::now());
        $campoClinico->setFechaFinal(Carbon::now()->addMonths(8));
        $campoClinico->setHorario('10am a 14:00pm');
        $campoClinico->setPromocion('Promoción');
        $campoClinico->setLugaresSolicitados(40);
        $campoClinico->setLugaresAutorizados(20);
        $campoClinico->setMonto(0);
        $campoClinico->setConvenio($convenio);
        $campoClinico->setEstatus($estatusCampo);
        $campoClinico->setUnidad($unidad);

        return $campoClinico;
    }
}
