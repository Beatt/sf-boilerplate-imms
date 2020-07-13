<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Form\Type\ComprobantePagoType\ComprobantePagoType;
use AppBundle\Repository\CampoClinicoRepository;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Security\Voter\SolicitudVoter;
use AppBundle\Service\UploaderComprobantePagoInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ie")
 */
class ComprobantePagoController extends DIEControllerController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/pagos/{id}/cargar-comprobante-de-pago", name="ie#cargar_comprobante_de_pago", methods={"POST"})
     * @param int $id
     * @param Request $request
     * @param UploaderComprobantePagoInterface $uploaderComprobantePago
     * @param PagoRepositoryInterface $pagoRepository
     * @return RedirectResponse
     */
    public function cargarComprobanteDePagoAction(
        $id,
        Request $request,
        UploaderComprobantePagoInterface $uploaderComprobantePago,
        PagoRepositoryInterface $pagoRepository
    ) {
        /** @var Pago $pago */
        $pago = $pagoRepository->find($id);
        if(!$pago) throw $this->createNotFindPagoException($id);

        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        $this->denyAccessUnlessGranted(SolicitudVoter::CARGAR_COMPROBANTE_DE_PAGO, $pago->getSolicitud());

        $form = $this->createForm(ComprobantePagoType::class, $pago, [
            'action' => $this->generateUrl('ie#cargar_comprobante_de_pago', [
                'id' => $id
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            /** @var Pago $pago */
            $pago = $form->getData();
            $uploaderComprobantePago->update($pago);

            $this->addFlash(
                'success',
                $this->getSuccessFlashMessage($pago)
            );
            return new RedirectResponse($request->headers->get('referer'));
        }

        $this->addFlash(
            'danger',
            $this->getFailedFlashMessage($pago)
        );
        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @return string
     */
    private function getSuccessFlashMessage(Pago $pago)
    {
        $solicitud = $pago->getSolicitud();
        if($solicitud->isPagoUnico()) {
            return sprintf(
                '¡El comprobante de la solicitud %s se ha cargado correctamente!',
                $solicitud->getNoSolicitud()
            );
        }
        elseif(!$solicitud->isPagoUnico()) {
            $campoClinico = $this->getCampoClinico($solicitud, $pago->getReferenciaBancaria());

            return sprintf(
                '¡El comprobante del campo clínico %s se ha cargado correctamente!',
                $campoClinico->getUnidad()->getNombre()
            );
        }

        $this->setCriticalLogUpdateComprobantePago($solicitud);

        return '';
    }

    /**
     * @return string
     */
    private function getFailedFlashMessage(Pago $pago)
    {
        $solicitud = $pago->getSolicitud();
        if($solicitud->isPagoUnico()) {
            return sprintf(
                '¡Lo sentimos! Ha ocurrido un problema al cargar el comprobante de pago para la solicitud %s',
                $solicitud->getNoSolicitud()
            );
        }
        elseif(!$solicitud->isPagoUnico()) {
            $campoClinico = $this->getCampoClinico($solicitud, $pago->getReferenciaBancaria());

            return sprintf(
                '¡Lo sentimos! Ha ocurrido un problema al cargar el comprobante de pago del campo clínico %s',
                $campoClinico->getUnidad()->getNombre()
            );
        }

        $this->setCriticalLogUpdateComprobantePago($solicitud);

        return '';
    }

    /**
     * @param Solicitud $solicitud
     * @param $referenciaBancaria
     * @return CampoClinico
     */
    private function getCampoClinico(Solicitud $solicitud, $referenciaBancaria)
    {
        /** @var CampoClinico $campoClinico */
        return $solicitud->getCamposClinicos()->matching(
            CampoClinicoRepository::getCampoClinicoByReferenciaBancaria($referenciaBancaria)
        )->first();
    }

    /**
     * @param Solicitud $solicitud
     */
    private function setCriticalLogUpdateComprobantePago(Solicitud $solicitud)
    {
        $this->logger->critical(sprintf('Se esta tratando de cargar un comprobante de pago sin haber asignado el tipo de pago'), [
            'id' => $solicitud->getId()
        ]);
    }
}
