<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fofoe")
 */
final class ValidarPagoController extends DIEControllerController
{
    /**
     * @Route("/pagos/{id}/validacion-de-pago", name="fofoe#validacion_de_pago")
     * @param $id
     * @return Response
     */
    public function validacionDePago($id)
    {
        return $this->render('fofoe/pago/validacion_de_pago.html.twig', [
        ]);
    }
}
