<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\EstatusCampo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EstatusCampoFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $this->create($manager, EstatusCampo::NUEVO, 1);
        $this->create($manager, EstatusCampo::PENDIENTE_DE_PAGO, 2);
    }

    /**
     * @param ObjectManager $manager
     * @param $typeReference
     * @param $id
     */
    private function create(ObjectManager $manager, $typeReference, $id)
    {
        //$estatusCampo = $manager->find(EstatusCampo::class, $id);
        $estatusCampo = new EstatusCampo();
        $estatusCampo->setNombre($typeReference);
        $manager->persist($estatusCampo);
        $this->addReference($typeReference, $estatusCampo);
    }
}
