<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\Repository\SolicitudRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ie")
 */
class CampoClinicoController extends DIEControllerController
{
    /**
     * @Route("/solicitudes/{id}/detalle-de-solicitud-multiple", name="ie#detalle_de_solicitud_multiple")
     * @param $id
     * @param SolicitudRepositoryInterface $solicitudRepository
     * @return Response
     */
    public function indexAction($id, SolicitudRepositoryInterface $solicitudRepository)
    {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();

        /** @var Solicitud $solicitud */
        $solicitud = $solicitudRepository->find($id);

        $serializer = $this->get('serializer');

        return $this->render('institucion_educativa/campo_clinico/detalle_de_solicitud_multiple.html.twig', [
            'institucionId' => $institucion->getId(),
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
                            'nombre'
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
}
