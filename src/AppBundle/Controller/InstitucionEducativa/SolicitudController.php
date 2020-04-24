<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Entity\Solicitud;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\ExpedienteRepositoryInterface;
use AppBundle\Repository\InstitucionRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SolicitudController extends Controller
{
    /**
     * @Route("/instituciones/{id}/solicitudes", methods={"GET"})
     */
    public function indexAction($id, InstitucionRepositoryInterface $institucionRepository)
    {
        $institucion = $institucionRepository->find($id);

        return $this->render('institucion_educativa/solicitud/index.html.twig', [
            'institucion' => $institucion
        ]);
    }

    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}", name="instituciones#show")
     * @param integer $id
     * @param $solicitudId
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param ExpedienteRepositoryInterface $expedienteRepository
     * @return Response
     */
    public function showAction(
        $id,
        $solicitudId,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        ExpedienteRepositoryInterface $expedienteRepository
    ) {
        $camposClinicos = $campoClinicoRepository->getAllCamposClinicosByRequest($solicitudId);
        $expedientes = $expedienteRepository->getAllExpedientesByRequest($solicitudId);
        $institucion = $institucionRepository->find($id);

        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        return $this->render('institucion_educativa/solicitud/show.html.twig',[
            'institucion' => $institucion,
            'solicitud' => $solicitud,
            'camposClinicos' => $this->get('serializer')->normalize(
                $camposClinicos,
                'json',
                [
                    'attributes' => [
                        'id',
                        'lugaresSolicitados',
                        'lugaresAutorizados',
                        'fechaInicial',
                        'fechaFinal',
                        'cicloAcademico' => [
                            'nombre'
                        ],
                        'carrera' => [
                            'nombre',
                            'nivelAcademico' => [
                                'nombre'
                            ]
                        ],
                        'solicitud' => [
                            'id',
                            'noSolicitud'
                        ]
                    ]
                ]
            ),
            'expediente' => $this->get('serializer')->normalize(
                $expedientes,
                'json',
                [
                    'attributes' => [
                        'id',
                        'descripcion',
                        'urlArchivo',
                        'fecha'
                    ]
                ]
            )
        ]);
    }
}
