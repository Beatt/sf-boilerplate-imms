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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/ie")
 */
final class CargaComprobantePagoController extends DIEControllerController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/pagos/{id}/carga-de-comprobante-de-pago", name="ie#carga_de_comprobante_de_pago")
     * @param $id
     * @param Request $request
     * @param PagoRepositoryInterface $pagoRepository
     * @param NormalizerInterface $normalizer
     * @param UploaderComprobantePagoInterface $uploaderComprobantePago
     * @return Response
     */
    public function cargaDeComprobanteDePago(
        $id,
        Request $request,
        PagoRepositoryInterface $pagoRepository,
        NormalizerInterface $normalizer,
        UploaderComprobantePagoInterface $uploaderComprobantePago
    ) {

        /** @var Pago $pago */
        $pago = $pagoRepository->find($id);
        if(!$pago) throw $this->createNotFindPagoException($id);

        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        $this->denyAccessUnlessGranted(SolicitudVoter::OBTENER_GESTION_DE_PAGOS, $pago->getSolicitud());

        $form = $this->createForm(ComprobantePagoType::class, $pago, [
            'action' => $this->generateUrl('ie#carga_de_comprobante_de_pago', [
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

            $solicitud = $pago->getSolicitud();
            return new RedirectResponse($this->getRedirectRoute($solicitud));
        }

        return $this->render('ie/solicitud/carga_de_comprobante_de_pago.html.twig', [
            'gestionPago' => $normalizer->normalize($pago->getGestionPago()),
            'id' => $id,
            'errors' => $this->getFormErrors($form)
        ]);
    }

    /**
     * @param Pago $pago
     * @return string
     */
    private function getSuccessFlashMessage(Pago $pago)
    {
        $solicitud = $pago->getSolicitud();
        if($solicitud->isPagoUnico()) {
            return sprintf(
                '¡El comprobante de la solicitud %s, con referencia %s, se ha cargado correctamente!',
                $solicitud->getNoSolicitud(), $solicitud->getReferenciaBancaria()
            );
        }
        elseif(!$solicitud->isPagoUnico()) {
            $campoClinico = $this->getCampoClinico($solicitud, $pago->getReferenciaBancaria());

            return sprintf(
                '¡El comprobante del campo clínico %s, con referencia %s, se ha cargado correctamente!',
                $campoClinico->getUnidad()->getNombre(), $campoClinico->getReferenciaBancaria()
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
        $this->logger->critical(sprintf('Se esta tratando de cargar un comprobante de pago sin haber asignado el tipo de pago a la solicitud'), [
            'id' => $solicitud->getId()
        ]);
    }

    /**
     * @param Solicitud $solicitud
     * @return string
     */
    private function getRedirectRoute(Solicitud $solicitud)
    {
        return $solicitud->isPagoUnico() ?
            $this->generateUrl('ie#detalle_de_solicitud', [
                'id' => $solicitud->getId()
            ]) :
            $this->generateUrl('ie#detalle_de_solicitud_multiple', [
                'id' => $solicitud->getId()
            ]);
    }
}
