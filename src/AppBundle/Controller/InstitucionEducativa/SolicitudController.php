<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\ExpedienteRepositoryInterface;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SolicitudController extends Controller
{
    /**
     * @Route("/instituciones/{id}/solicitudes", methods={"GET"})
     * @param $id
     * @param Request $request
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param SolicitudRepositoryInterface $solicitudRepository
     * @return Response
     */
    public function indexAction(
        $id,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        SolicitudRepositoryInterface $solicitudRepository
    ) {
        /** @var Institucion $institucion */
        $institucion = $institucionRepository->find($id);

        $isOffsetSet = $request->query->get('offset');
        $isSearchSet = $request->query->get('search');

        $offset = $request->query->getInt('offset', 1);
        $search = $request->query->get('search', null);

        $camposClinicos = $solicitudRepository->getAllSolicitudesByInstitucion(
            $id,
            $offset,
            $search
        );

        if(isset($isOffsetSet) || isset($isSearchSet)) {
            return new JsonResponse([
                'camposClinicos' => $this->getNormalizeSolicitudes($camposClinicos),
                'total' => round(count($camposClinicos) / SolicitudRepositoryInterface::PAGINATOR_PER_PAGE)
            ]);

        }

        return $this->render('institucion_educativa/solicitud/index.html.twig', [
            'institucion' => $institucion,
            'camposClinicos' => $this->getNormalizeSolicitudes($camposClinicos),
            'total' => round(count($camposClinicos) / SolicitudRepositoryInterface::PAGINATOR_PER_PAGE)
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

    /**
     * @param $camposClinicos
     * @return array
     */
    private function getNormalizeSolicitudes($camposClinicos)
    {
        return $this->get('serializer')->normalize(
            $camposClinicos,
            'json',
            [
                'attributes' => [
                    'id',
                    'noSolicitud',
                    'fecha',
                    'estatus' => [
                        'nombre',
                        'estatus'
                    ],
                    'noCamposSolicitados',
                    'noCamposAutorizados'
                ]
            ]);
    }
}
