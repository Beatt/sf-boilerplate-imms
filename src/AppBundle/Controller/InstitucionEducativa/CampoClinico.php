<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Controller\DIEControllerController;
use AppBundle\DTO\UploadComprobantePagoDTO;
use AppBundle\Entity\Solicitud;
use AppBundle\Form\Type\ComprobantePagoType;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Service\UploaderComprobantePagoInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampoClinico extends DIEControllerController
{
    /**
     * @Route("/instituciones/{institucionId}/solicitudes/{solicitudId}/campos-clinicos", name="campos_clinicos#index")
     * @param int $institucionId
     * @param int $solicitudId
     * @param SolicitudRepositoryInterface $solicitudRepository
     * @return Response
     */
    public function indexAction($institucionId, $solicitudId, SolicitudRepositoryInterface $solicitudRepository)
    {
        /** @var Solicitud $solicitud */
        $solicitud = $solicitudRepository->find($solicitudId);

        $serializer = $this->get('serializer');

        return $this->render('institucion_educativa/campo_clinico/index.html.twig', [
            'institucionId' => $institucionId,
            'noSolicitud' => $solicitud->getId(),
            'expediente' => $serializer->normalize(
                $solicitud,
                'json',
                [
                    'attributes' => [
                        'documento',
                        'urlArchivo',
                        'descripcion',
                        'fechaComprobante'
                    ]
                ]
            ),
            'camposClinicos' => $serializer->normalize(
                $solicitud->getCamposClinicos(),
                'json',
                [
                    'attributes' => [
                        'id',
                        'unidad' => [
                            'tipoUnidad' => [
                                'nombre'
                            ]
                        ],
                        'convenio' => [
                            'carrera' => [
                                'nombre',
                                'nivelAcademico' => [
                                    'nombre'
                                ]
                            ],
                            'cicloAcademico' => [
                                'nombre'
                            ]
                        ],
                        'lugaresSolicitados',
                        'lugaresAutorizados',
                        'fechaInicial',
                        'fechaFinal',
                        'estatus' => [
                            'nombre'
                        ],
                        'comprobante',
                        'factura',
                    ]
                ]
            )
        ]);
    }

    /**
     * @Route("/campos-clinicos:uploadComprobantePago", name="campos_clinicos#uploadComprobantePago", methods={"POST"})
     * @param Request $request
     * @param UploaderComprobantePagoInterface $uploaderComprobantePago
     * @return JsonResponse
     */
    public function uploadComprobantePagoAction(Request $request, UploaderComprobantePagoInterface $uploaderComprobantePago)
    {
        $form = $this->createForm(ComprobantePagoType::class, null, [
            'action' => $this->generateUrl('campos_clinicos#uploadComprobantePago'),
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
