<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\ObjectValues\PagoId;
use AppBundle\Repository\Fofoe\ValidacionDePago\DetallePago;
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
     * @param DetallePago $detallePago
     * @param NormalizerInterface $normalizer
     * @return Response
     */
    public function validacionDePago(
        $id,
        DetallePago $detallePago,
        NormalizerInterface $normalizer
    ) {

        $pago = $detallePago->detalleByPago(PagoId::fromString($id));

        dump($pago);

        return $this->render('fofoe/pago/validacion_de_pago.html.twig', [
            'pago' => $normalizer->normalize($pago, 'json')
        ]);
    }
}
