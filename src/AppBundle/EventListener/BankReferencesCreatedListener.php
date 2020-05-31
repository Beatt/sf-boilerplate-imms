<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\SolicitudInterface;
use AppBundle\Event\BankReferencesCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;

class BankReferencesCreatedListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handleBankReferencesCreated(BankReferencesCreatedEvent $event)
    {
        $solicitud = $event->getSolicitud();

        if($solicitud->getEstatus() !== SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS) return;

        $solicitud->setEstatus(SolicitudInterface::CARGANDO_COMPROBANTES);
        $this->entityManager->flush();
    }
}
