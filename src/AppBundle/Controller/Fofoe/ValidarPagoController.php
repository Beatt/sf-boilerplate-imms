<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Pago;
use AppBundle\Form\Type\ValidacionDePago\ValidacionPagoType;
use AppBundle\ObjectValues\PagoId;
use AppBundle\Repository\Fofoe\ValidacionDePago\DetallePago;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Service\ProcesadorValidarPagoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/fofoe")
 */
final class ValidarPagoController extends DIEControllerController
{
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

            $this->addFlash('success', '¡Se ha actualizado correctamente su pago!');

            return $this->redirectToRoute('fofoe/inicio');
        }

        $pagoDetalle = $detallePago->detalleByPago(PagoId::fromString($id));

        return $this->render('fofoe/pago/validacion_de_pago.html.twig', [
            'pago' => $normalizer->normalize($pagoDetalle, 'json'),
            'errors' => $this->getFormErrors($form)
        ]);
    }
}
