<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RegionFixture extends Fixture
{
    const REGION_A = 'Region a';
    const REGION_B = 'Region b';

    public function load(ObjectManager $manager)
    {
        $this->create(
            self::REGION_A,
            $manager
        );

        $this->create(
            self::REGION_B,
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
        $region = new Region();
        $region->setNombre($typeReference);
        $region->setActivo(true);
        $manager->persist($region);
        $manager->flush();

        $this->addReference($typeReference, $region);
    }
}
