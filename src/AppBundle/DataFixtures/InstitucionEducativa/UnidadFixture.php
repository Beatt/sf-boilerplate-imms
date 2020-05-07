<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\Unidad;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UnidadFixture extends Fixture implements DependentFixtureInterface
{
    const UNIDAD_A = 'Unidad a';
    const UNIDAD_B = 'Unidad b';

    public function load(ObjectManager $manager)
    {
        $this->create(
            self::UNIDAD_A,
            $manager,
            TipoUnidadFixture::TIPO_UNIDAD_A,
            DelegacionFixture::DELEGACION_A
        );

        $this->create(
            self::UNIDAD_B,
            $manager,
            TipoUnidadFixture::TIPO_UNIDAD_B,
            DelegacionFixture::DELEGACION_B
        );
    }

    /**
     * @param string $typeReference
     * @param ObjectManager $manager
     * @param $tipoUnidadTypeReference
     * @param $delegacionTypeReference
     */
    private function create(
        $typeReference,
        ObjectManager $manager,
        $tipoUnidadTypeReference,
        $delegacionTypeReference
    ) {
        $unidad = new Unidad();
        $unidad->setNombre($typeReference);
        $unidad->setClaveUnidad('cu');
        $unidad->setClaveUnidadPrincipal('cp');
        $unidad->setClavePresupuestal('cp');
        $unidad->setEsUmae(true);
        $unidad->setAnio(2010);
        $unidad->setActivo(true);
        $unidad->setTipoUnidad($this->getReference($tipoUnidadTypeReference));
        $unidad->setDelegacion($this->getReference($delegacionTypeReference));
        $manager->persist($unidad);
        $manager->flush();

        $this->addReference($typeReference, $unidad);
    }

    function getDependencies()
    {
        return [
            DelegacionFixture::class,
            TipoUnidadFixture::class
        ];
    }
}
