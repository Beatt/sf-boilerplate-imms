<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampo;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Event\ReferenciaBancariaDownloadedEvent;
use Doctrine\ORM\EntityManagerInterface;

class ReferenciaBancariaDownloadedListener
{
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  public function handleReferenciaBancariaDownloaded(ReferenciaBancariaDownloadedEvent $event)
  {
    $solicitud = $event->getSolicitud();

    if($solicitud->getEstatus() !== SolicitudInterface::FORMATOS_DE_PAGO_GENERADOS) return;
    $solicitud->setEstatus(SolicitudInterface::CARGANDO_COMPROBANTES);
    $this->entityManager->flush();

    $this->setPendienteDePagoEstatusACamposClinicos($solicitud);
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
    $this->entityManager->flush();
  }
}
