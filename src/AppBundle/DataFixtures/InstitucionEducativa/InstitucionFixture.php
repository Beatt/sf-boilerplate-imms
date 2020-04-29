<?php

namespace AppBundle\DataFixtures\InstitucionEducativa;

use AppBundle\Entity\Institucion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class InstitucionFixture extends Fixture implements FixtureInterface
{
    const INSTITUCION_A = 'institucion_a';

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
        $institucion->setRepresentante('Representante 1');
        $institucion->setRepresentante('Gabriel Perez');

        $manager->persist($institucion);
        $manager->flush();

        $this->addReference(self::INSTITUCION_A, $institucion);
    }
}
