<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\EstatusCampoRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
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

    /**
     * @var SolicitudRepositoryInterface
     */
    private $solicitudRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EstatusCampoRepositoryInterface $estatusCampoRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        SolicitudRepositoryInterface $solicitudRepository
    ) {
        $this->entityManager = $entityManager;
        $this->estatusCampoRepository = $estatusCampoRepository;
        $this->campoClinicoRepository = $campoClinicoRepository;
        $this->solicitudRepository = $solicitudRepository;
    }

    public function postUpload(Event $event)
    {
        /** @var Pago $pago */
        $pago = $event->getObject();

        $estatusPagado = $this->estatusCampoRepository->getEstatusPagado();

        $pago->getSolicitud()->setEstatus(SolicitudInterface::EN_VALIDACION_FOFOE);

        if($pago->getSolicitud()->isPagoUnico()) {
            /** @var CampoClinico $camposClinico */
            foreach($pago->getSolicitud()->getCamposClinicos() as $camposClinico) {
                $camposClinico->setEstatus($estatusPagado);
            }
        } else {
            $camposClinico = $this->campoClinicoRepository->findOneBy(['referenciaBancaria' => $pago->getReferenciaBancaria()]);
            $camposClinico->setEstatus($estatusPagado);
        }

        $this->entityManager->flush();
    }
}
