<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\TipoUnidad;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TipoUnidadFixture extends Fixture
{
    const TIPO_UNIDAD_A = 'Tipo unidad a';
    const TIPO_UNIDAD_B = 'Tipo unidad b';

    public function load(ObjectManager $manager)
    {
        $this->create(
            self::TIPO_UNIDAD_A,
            $manager
        );

        $this->create(
            self::TIPO_UNIDAD_B,
            $manager
        );
    }

    /**
     * @param string $typeReference
     * @param ObjectManager $manager
     */
    private function create(
        $typeReference,
        ObjectManager $manager
    ) {
        $tipoUnidad = new TipoUnidad();
        $tipoUnidad->setNombre($typeReference);
        $tipoUnidad->setActivo(true);
        $tipoUnidad->setNivel(0);
        $tipoUnidad->setGrupoTipo('dummy');
        $tipoUnidad->setGrupoNombre('dummy');
        $tipoUnidad->setDescripcion('dummydata');
        $manager->persist($tipoUnidad);
        $manager->flush();

        $this->addReference($typeReference, $tipoUnidad);
    }
}
