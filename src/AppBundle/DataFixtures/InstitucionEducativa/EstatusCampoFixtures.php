<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;


use AppBundle\Entity\EstatusCampo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EstatusCampoFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $this->create($manager, EstatusCampo::MONTOS_VALIDADOS, 11);
    }

    /**
     * @param ObjectManager $manager
     * @param $typeReference
     * @param $id
     */
    private function create(ObjectManager $manager, $typeReference, $id)
    {
        $estatusCampo = $manager->find(EstatusCampo::class, $id);
        $this->addReference($typeReference, $estatusCampo);
    }
}
