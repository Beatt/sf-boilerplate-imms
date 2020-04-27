<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\CicloAcademico;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CicloAcademicoFixture extends Fixture
{
    const CICLO_A = 'ciclo_a';
    const CICLO_B = 'ciclo_b';
    const CICLO_C = 'ciclo_c';

    public function load(ObjectManager $manager)
    {
        $this->create('Ciclo a', self::CICLO_A, $manager);
        $this->create('Ciclo b', self::CICLO_B, $manager);
        $this->create('Ciclo b', self::CICLO_C, $manager);
    }

    private function create($name, $referenceType, ObjectManager $manager)
    {
        $cicloAcademico = new CicloAcademico();
        $cicloAcademico->setNombre($name);
        $cicloAcademico->setActivo(true);
        $this->addReference($referenceType, $cicloAcademico);

        $manager->persist($cicloAcademico);
        $manager->flush();
    }
}
