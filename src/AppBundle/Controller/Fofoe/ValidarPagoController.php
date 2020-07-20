<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Form\Type\ValidacionDePago\ValidacionPagoType;
use AppBundle\ObjectValues\PagoId;
use AppBundle\Repository\CampoClinicoRepository;
use AppBundle\Repository\Fofoe\ValidacionDePago\DetallePago;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Service\ProcesadorValidarPagoInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/fofoe")
 */
final class ValidarPagoController extends DIEControllerController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/pagos/{id}/validacion-de-pago", name="fofoe#validacion_de_pago")
     * @param $id
     * @param Request $request
     * @param DetallePago $detallePago
     * @param NormalizerInterface $normalizer
     * @param PagoRepositoryInterface $pagoRepository
     * @param ProcesadorValidarPagoInterface $procesadorValidarPago
     * @return Response
     */
    public function validacionDePago(
        $id,
        Request $request,
        DetallePago $detallePago,
        NormalizerInterface $normalizer,
        PagoRepositoryInterface $pagoRepository,
        ProcesadorValidarPagoInterface $procesadorValidarPago
    ) {
        $pago = $pagoRepository->find($id);
        $form = $this->createForm(ValidacionPagoType::class, $pago, [
            'action' => $this->generateUrl('fofoe#validacion_de_pago', [
                'id' => $id
            ])
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Pago $pago */
            $pago = $form->getData();
            $procesadorValidarPago->procesar($pago);

            $this->addFlash('success', $this->getSuccessFlashMessage($pago));

            return $this->redirectToRoute('fofoe/inicio');
        }

        $pagoDetalle = $detallePago->detalleByPago(PagoId::fromString($id));

        return $this->render('fofoe/pago/validacion_de_pago.html.twig', [
            'pago' => $normalizer->normalize($pagoDetalle, 'json'),
            'errors' => $this->getFormErrors($form)
        ]);
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
