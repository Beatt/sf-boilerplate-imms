<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\DTO\UploadComprobantePagoDTO;
use AppBundle\Form\Type\ComprobantePagoType;
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

        /** @var UploadComprobantePagoDTO $data */
        $data = $form->getData();
        if($form->isSubmitted() && $form->isValid()) {
            $isComprobantePagoUploaded = $uploaderComprobantePago->update(
                $data->getCampoClinico(),
                $data->getFile()
            );

            return $isComprobantePagoUploaded ?
                $this->successResponse('Se ha cargado correctamente el comprobante de pago') :
                $this->failedResponse('¡Ha ocurrido un error, vuelve a intentar más tarde!');
        }

        return $this->jsonErrorResponse($form);
    }
}
