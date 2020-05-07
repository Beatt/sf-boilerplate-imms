<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\Delegacion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DelegacionFixture extends Fixture implements DependentFixtureInterface
{
    const DELEGACION_A = 'Delegación a';
    const DELEGACION_B = 'Delegación b';

    public function load(ObjectManager $manager)
    {
        $this->create(
            self::DELEGACION_A,
            $manager,
            RegionFixture::REGION_A
        );

        $this->create(
            self::DELEGACION_B,
            $manager,
            RegionFixture::REGION_B
        );
    }

    /**
     * @param string $typeReference
     * @param ObjectManager $manager
     * @param $regionTypeReference
     */
    private function create(
        $typeReference,
        ObjectManager $manager,
        $regionTypeReference
    ) {
        $delegacion = new Delegacion();
        $delegacion->setNombre($typeReference);
        $delegacion->setActivo(true);
        $delegacion->setClaveDelegacional('aa');
        $delegacion->setGrupoDelegacion('gp');
        $delegacion->setNombreGrupoDelegacion('dummydata');
        $delegacion->setRegion($this->getReference($regionTypeReference));
        $manager->persist($delegacion);
        $manager->flush();

        $this->addReference($typeReference, $delegacion);
    }

    function getDependencies()
    {
        return [
            RegionFixture::class
        ];
    }
}
