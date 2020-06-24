<?php

namespace AppBundle\Service;

use AppBundle\Entity\Institucion;
use Doctrine\ORM\EntityManagerInterface;

class InstitucionManager implements InstitucionManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Institucion $institucion)
    {
        $this->entityManager->persist($institucion);
        $this->entityManager->flush();
    }
}
