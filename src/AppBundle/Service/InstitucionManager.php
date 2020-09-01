<?php

namespace AppBundle\Service;

use AppBundle\Entity\Institucion;
use AppBundle\Event\InstitucionEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class InstitucionManager implements InstitucionManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    public function update(Institucion $institucion)
    {
        $this->entityManager->persist($institucion);
        $this->entityManager->flush();

      $this->dispatcher->dispatch(
        InstitucionEvent::DATOS_ACTUALIZADOS,
        new InstitucionEvent($institucion)
      );
    }
}
