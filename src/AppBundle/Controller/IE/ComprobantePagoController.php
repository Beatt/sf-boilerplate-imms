<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\DTO\UploadComprobantePagoDTO;
use AppBundle\Entity\Pago;
use AppBundle\Form\Type\ComprobantePagoType\ComprobantePagoType;
use AppBundle\Service\UploaderComprobantePagoInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ie")
 */
class ComprobantePagoController extends DIEControllerController
{
    /**
     * @Route("/cargar-comprobante-de-pago", name="ie#cargar_comprobante_de_pago", methods={"POST"})
     * @param Request $request
     * @param UploaderComprobantePagoInterface $uploaderComprobantePago
     * @return JsonResponse
     */
    public function cargarComprobanteDePagoAction(Request $request, UploaderComprobantePagoInterface $uploaderComprobantePago)
    {
        $form = $this->createForm(ComprobantePagoType::class, null, [
            'action' => $this->generateUrl('ie#cargar_comprobante_de_pago'),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            /** @var Pago $pago */
            $pago = $form->getData();
            $isComprobantePagoUploaded = $uploaderComprobantePago->update($pago);

            return $isComprobantePagoUploaded ?
                $this->successResponse('Se ha cargado correctamente el comprobante de pago') :
                $this->failedResponse('¡Ha ocurrido un error, vuelve a intentar más tarde!');
        }

        return $this->jsonErrorResponse($form);
    }
}
