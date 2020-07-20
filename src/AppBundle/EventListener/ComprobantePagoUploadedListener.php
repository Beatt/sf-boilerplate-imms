<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Pago;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\EstatusCampoRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
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

    /**
     * @var PagoRepositoryInterface
     */
    private $pagoRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EstatusCampoRepositoryInterface $estatusCampoRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        SolicitudRepositoryInterface $solicitudRepository,
        PagoRepositoryInterface $pagoRepository
    ) {
        $this->entityManager = $entityManager;
        $this->estatusCampoRepository = $estatusCampoRepository;
        $this->campoClinicoRepository = $campoClinicoRepository;
        $this->solicitudRepository = $solicitudRepository;
        $this->pagoRepository = $pagoRepository;
    }

    public function comprobantePagoUploaded(Event $event)
    {
        /** @var Pago $pago */
        $pago = $event->getObject();
        if(!$event->getObject() instanceof Pago) return;

        $estatusPagado = $this->estatusCampoRepository->getEstatusPagado();

        if($pago->getSolicitud()->isPagoUnico()) {
            /** @var CampoClinico $camposClinico */
            foreach($pago->getSolicitud()->getCamposClinicos() as $camposClinico) {
                $camposClinico->setEstatus($estatusPagado);
            }
            $this->settingSolicitudEnValidacionFOFOE($pago);
        } else {
            $this->ActualizarEstatusDeCampoClinicoActual($pago, $estatusPagado);
            if($this->isSolicitudListaParaCambiarDeEstatus($pago)) $this->settingSolicitudEnValidacionFOFOE($pago);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Pago $pago
     * @return bool
     */
    private function isSolicitudListaParaCambiarDeEstatus(Pago $pago)
    {
        return count($this->getCamposClinicosSinComprobantesDePagoCargados($pago)) === 0;
    }

    /**
     * @param Pago $pago
     * @return array
     */
    private function getCamposClinicosSinComprobantesDePagoCargados(Pago $pago)
    {
        return array_filter(
            $pago->getSolicitud()->getCamposClinicos()->toArray(),
            function (CampoClinico $campoClinico) {
                return $campoClinico->getEstatus()->getNombre() === EstatusCampoInterface::PENDIENTE_DE_PAGO;
            });
    }

    /**
     * @param Pago $pago
     * @param $estatusPagado
     */
    private function ActualizarEstatusDeCampoClinicoActual(Pago $pago, $estatusPagado)
    {
        dump($estatusPagado);
        $camposClinico = $this->campoClinicoRepository->findOneBy([
            'referenciaBancaria' => $pago->getReferenciaBancaria()
        ]);
        $camposClinico->setEstatus($estatusPagado);
    }

    /**
     * @param Pago $pago
     */
    private function settingSolicitudEnValidacionFOFOE(Pago $pago)
    {
        $pago->getSolicitud()->setEstatus(SolicitudInterface::EN_VALIDACION_FOFOE);
    }
}
