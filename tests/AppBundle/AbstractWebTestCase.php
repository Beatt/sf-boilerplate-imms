<?php

namespace Tests\AppBundle;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractWebTestCase extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    protected function setUp()
    {
        $client = self::bootKernel();

        $this->container = $client->getContainer();
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');

        $this->clearTablaCampoClinico();
        $this->clearTablaPago();
        $this->clearTablaSolicitud();
    }

    protected function clearTablaCampoClinico()
    {
        $this->entityManager->getConnection()->exec('DELETE FROM campo_clinico');
    }

    protected function clearTablaSolicitud()
    {
        $this->entityManager->getConnection()->exec('DELETE FROM solicitud');
    }

    protected function clearTablaPago()
    {
        $this->entityManager->getConnection()->exec('DELETE FROM pago');
    }

    protected function clearTablaPagoById($id)
    {
        $this->entityManager->getConnection()->exec('DELETE FROM pago WHERE id = ' . $id);
    }
}
