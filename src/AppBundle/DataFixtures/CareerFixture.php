<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Carrera;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CareerFixture extends Fixture implements FixtureInterface, DependentFixtureInterface
{
    const CARRERA_A = 'career_a';

    public function load(ObjectManager $manager)
    {
        $carrera = new Carrera();
        $carrera->setNombre('Carrera a');
        $carrera->setActivo(true);
        $carrera->setNivelAcademico($this->getReference(NivelAcademicoFixture::NIVEL_ACADEMICO_A));

        $manager->persist($carrera);
        $manager->flush();

        $this->addReference(self::CARRERA_A, $carrera);
    }

    function getDependencies()
    {
        return[
            NivelAcademicoFixture::class
        ];
    }
}
