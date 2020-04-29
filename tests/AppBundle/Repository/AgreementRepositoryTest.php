<?php

namespace Tests\AppBundle\Repository;

use AppBundle\DataFixtures\ConvenioFixture;
use AppBundle\DataFixtures\InstitucionFixture;
use AppBundle\Entity\Convenio;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AgreementRepositoryTest extends WebTestCase
{

    /**
     * @var EntityManagerInterface
     */
    private $doctrine;

    /*public function setUp()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $this->doctrine = $container->get('doctrine');

        $purger = new ORMPurger($this->doctrine->getManager());
        $purger->purge();

        $entityManager = $this->doctrine->getManager();

        $institutionFixture = new InstitucionFixture();
        $institutionFixture->load($entityManager);

        $agreementsFixture = new ConvenioFixture();
        $agreementsFixture->load($entityManager);

    }

    public function testAgreementsGreaterThanOneYear()
    {
        $agreements = $this->doctrine->getRepository(Convenio::class)
            ->getAgreementsGreaterThanOneYear();

        $this->assertEquals(3, count($agreements));
    }

    public function testAgreementsLessThanOneYearAndGreaterThanSixMonths()
    {
        $agreements = $this->doctrine->getRepository(Convenio::class)
            ->testAgreementsLessThanOneYearAndGreaterThanSixMonths();

        $this->assertEquals(2, count($agreements));
    }

    public function testAgreementsLessThanSixMonths()
    {
        $agreements = $this->doctrine->getRepository(Convenio::class)
            ->testAgreementsLessThanSixMonths();

        $this->assertEquals(1, count($agreements));
    }*/

}
