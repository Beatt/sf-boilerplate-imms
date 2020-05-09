<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Pago;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\EstatusCampoRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Vich\UploaderBundle\Event\Event;

class ComprobantePagoUploadedListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EstatusCampoRepositoryInterface
     */
    private $estatusCampoRepository;

    /**
     * @var CampoClinicoRepositoryInterface
     */
    private $campoClinicoRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EstatusCampoRepositoryInterface $estatusCampoRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository
    ) {
        $this->entityManager = $entityManager;
        $this->estatusCampoRepository = $estatusCampoRepository;
        $this->campoClinicoRepository = $campoClinicoRepository;
    }

    public function postUpload(Event $event)
    {
        /** @var Pago $pago */
        $pago = $event->getObject();
        $campoClinico = $this->campoClinicoRepository->findOneBy(['referenciaBancaria' => $pago->getReferenciaBancaria()]);

        $estatusPagado = $this->estatusCampoRepository->getEstatusPagado();
        $campoClinico->setEstatus($estatusPagado);

        $this->entityManager->flush();
    }
}
