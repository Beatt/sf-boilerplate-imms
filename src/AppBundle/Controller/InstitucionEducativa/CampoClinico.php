<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Entity\Solicitud;
use AppBundle\Repository\SolicitudRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampoClinico extends Controller
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

        return $this->render('institucion_educativa/campo_clinico/index.html.twig', [
            'institucionId' => $institucionId,
            'solicitud' => $solicitud,
            'camposClinicos' => $this->get('serializer')
                ->normalize(
                    $solicitud->getCamposClinicos(),
                    'json',
                    [
                        'attributes' => [
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
                            'factura'
                        ]
                    ]
                )
        ]);
    }
}
