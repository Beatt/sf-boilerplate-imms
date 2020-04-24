<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\NivelAcademico;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class NivelAcademicoFixture extends Fixture
{
    const NIVEL_ACADEMICO_A = 'nivel_academico_a';
    const NIVEL_ACADEMICO_B = 'nivel_academico_b';

    public function load(ObjectManager $manager)
    {
        $this->create(
            self::NIVEL_ACADEMICO_A,
            $manager
        );

        $this->create(
            self::NIVEL_ACADEMICO_B,
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
        $nivelAcademico = new NivelAcademico();
        $nivelAcademico->setNombre('Nivel academico a');
        $manager->persist($nivelAcademico);

        $this->addReference($typeReference, $nivelAcademico);
    }
}
