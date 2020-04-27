<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;


use AppBundle\Entity\EstatusCampo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EstatusCampoFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $this->create($manager, EstatusCampo::SOLICITUD_CONFIRMADA);
        $this->create($manager, EstatusCampo::SOLICITUD_NO_AUTORIZADA);
    }

    /**
     * @param ObjectManager $manager
     * @param $typeReference
     */
    private function create(ObjectManager $manager, $typeReference)
    {
        $estatusCampo = new EstatusCampo();
        $estatusCampo->setEstatus($typeReference);
        $manager->persist($estatusCampo);
        $manager->flush();
        $this->addReference($typeReference, $estatusCampo);
    }
}
