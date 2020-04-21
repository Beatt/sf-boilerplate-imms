<?php

namespace Tests\AppBundle\DataFixtures;

use AppBundle\Entity\Institucion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class InstitutionFixture extends Fixture implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $institucion = new Institucion();
        $institucion->setNombre('Institución a');
        $institucion->setRfc('20321032103');
        $institucion->setTelefono("505050030");
        $institucion->setCorreo("instituciona@example.com");
        $institucion->setFax('10102010');
        $institucion->setSitioWeb('www.instituciona.com');
        $institucion->setCedulaIdentificacion('http://www.localhost/cedulafiscal.pdf');
        $institucion->setDireccion('3 poniente');

        $manager->persist($institucion);
        $manager->flush();
    }
}
