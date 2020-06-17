<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Event\ReferenciaBancariaZipUnloadedEvent;
use Doctrine\ORM\EntityManagerInterface;

class ReferenciaBancariaZipUnloadedListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function referenciaBancariaZipUnloaded(ReferenciaBancariaZipUnloadedEvent $event)
    {
        $solicitud = $event->getSolicitud();

        $solicitud->setEstatus(SolicitudInterface::CARGANDO_COMPROBANTES);
        $this->setPendienteDePagoEstatusACamposClinicos($solicitud);

        $this->entityManager->flush();
    }

    /**
     * @param Solicitud $solicitud
     */
    protected function setPendienteDePagoEstatusACamposClinicos(Solicitud $solicitud)
    {
        /** @var EstatusCampo $pendienteDePagoEstatus */
        $pendienteDePagoEstatus = $this
            ->entityManager
            ->getRepository(EstatusCampo::class)
            ->findOneBy(['nombre' => EstatusCampoInterface::PENDIENTE_DE_PAGO]);

        /** @var CampoClinico $camposClinico */
        foreach ($solicitud->getCamposClinicos() as $camposClinico) {
            $camposClinico->setEstatus($pendienteDePagoEstatus);
        }
    }
}
