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
        $isTipoPagoSet = $request->query->get('search');
        $isEstatusSet = $request->query->get('estatus');

        $offset = $request->query->getInt('offset', 1);
        $search = $request->query->get('search', null);
        $tipoPago = $request->query->get('tipoPago', 'unico');
        $estatus = $request->query->get('estatus', null);

        $camposClinicos = $solicitudRepository->getAllSolicitudesByInstitucion(
            $id,
            $tipoPago,
            $estatus,
            $offset,
            $search
        );

        if(
            isset($isOffsetSet) ||
            isset($isSearchSet) ||
            isset($isTipoPagoSet) ||
            isset($isEstatusSet)
        ) {
            return new JsonResponse([
                'camposClinicos' => $this->getNormalizeSolicitudes($camposClinicos),
                'total' => round(count($camposClinicos) / SolicitudRepositoryInterface::PAGINATOR_PER_PAGE)
            ]);

        }

        return $this->render('institucion_educativa/solicitud/index.html.twig', [
            'institucion' => $institucion,
            'total' => round(count($camposClinicos) / SolicitudRepositoryInterface::PAGINATOR_PER_PAGE)
        ]);
    }

    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}", name="instituciones#show", methods={"GET"})
     * @param integer $id
     * @param $solicitudId
     * @param Request $request
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param ExpedienteRepositoryInterface $expedienteRepository
     * @return Response
     */
    public function showAction(
        $id,
        $solicitudId,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        ExpedienteRepositoryInterface $expedienteRepository
    ) {


        $isSearchSet = $request->query->get('search');

        $search = $request->query->get('search', null);

        $camposClinicos = $campoClinicoRepository->getAllCamposClinicosByRequest(
            $solicitudId,
            $search,
            false
        );

        $expediente = $expedienteRepository->getAllExpedientesByRequest($solicitudId);
        $institucion = $institucionRepository->find($id);

        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        $totalCampos = count($camposClinicos);

        $acc = 0;
        
        foreach ($camposClinicos as $campoClinico) {
            if($campoClinico->getLugaresAutorizados() > 0){
                $acc++;
            }
        }

        if(
            isset($isSearchSet) 
        ) {
            return new JsonResponse([
                'totalCampos' => $totalCampos,
                'autorizado' => $acc,
                'camposClinicos' => $this->getNormalizeCamposClinicos($camposClinicos)
            ]);

        }

        return $this->render('institucion_educativa/solicitud/show.html.twig',[
            'institucion' => $institucion,
            'solicitud' => $solicitud,
            'totalCampos' => $totalCampos,
            'autorizado' => $acc,
            'camposClinicos' => $this->getNormalizeCamposClinicos($camposClinicos),
            'expediente' => $this->getNormalizeExpediente($expediente),
            'search' => $search
        ]);
    }


    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/registrar", name="instituciones#record", methods={"POST", "GET"})
     * @param integer $id
     * @param $solicitudId
     * @param Request $request
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param ExpedienteRepositoryInterface $expedienteRepository
     * @return Response
     */
    public function recordAction(
        $id,
        $solicitudId,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        ExpedienteRepositoryInterface $expedienteRepository
    ) {

        $camposClinicos = $campoClinicoRepository->getAllCamposClinicosByRequest(
            $solicitudId, 
            null,
            true
        );

        $institucion = $institucionRepository->find($id);

        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        $acc = 0;
        
        foreach ($camposClinicos as $campoClinico) {
            if($campoClinico->getLugaresAutorizados() > 0){
                $acc++;
            }
        }

        return $this->render('institucion_educativa/solicitud/recordAmount.html.twig',[
            'institucion' => $institucion,
            'solicitud' => $solicitud,
            'autorizado' => $acc,
            'camposClinicos' => $this->getNormalizeCamposClinicos($camposClinicos)
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
                    'estatusActual',
                    'noCamposSolicitados',
                    'noCamposAutorizados'
                ]
            ]);
    }

    /**
     * @param $camposClinicos
     * @return array
     */
    private function getNormalizeCamposClinicos($camposClinicos)
    {

        return $this->get('serializer')->normalize(
            $camposClinicos,
            'json',
            [
                'attributes' => [
                    'id',
                    'lugaresSolicitados',
                    'lugaresAutorizados',
                    'fechaInicial',
                    'fechaFinal',
                    'weeks',
                    'cicloAcademico' => [
                        'nombre'
                    ],
                    'carrera' => [
                        'id',
                        'nombre',
                        'nivelAcademico' => [
                            'nombre'
                        ]
                    ],
                    'solicitud' => [
                        'id',
                        'noSolicitud',
                        'estatus'
                    ],
                    'unidad' => [
                        'nombre'
                    ]
                ]
            ]);
    }

    private function getNormalizeExpediente($expediente)
    {

        return $this->get('serializer')->normalize(
            $expediente,
            'json',
            [
                'attributes' => [
                    'id',
                    'descripcion',
                    'urlArchivo',
                    'fecha'
                ]
            ]
        );
    }
}