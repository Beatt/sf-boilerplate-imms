<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Form\Type\MontoCarreraType;
use AppBundle\Form\Type\SolicitudMontoType;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\MontoCarrera;
use AppBundle\Entity\Solicitud;
use AppBundle\Service\MontoCarreraManagerInterface;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\ExpedienteRepositoryInterface;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Repository\CarreraRepositoryInterface;
use AppBundle\Repository\MontoCarreraRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
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

        list($isOffsetSet, $isSearchSet, $isTipoPagoSet) = $this->setFilters($request);
        list($offset, $search, $tipoPago) = $this->initializeFiltersWithDefaultValues($request);

        $camposClinicos = $solicitudRepository->getAllSolicitudesByInstitucion(
            $id,
            $tipoPago,
            $offset,
            $search
        );

        if($this->isRequestedToFilter($isOffsetSet, $isSearchSet, $isTipoPagoSet)) {
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
     * @param PagoRepositoryInterface $pagoRepository
     * @return Response
     */
    public function showAction(
        $id,
        $solicitudId,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        ExpedienteRepositoryInterface $expedienteRepository,
        PagoRepositoryInterface $pagoRepository
    ) {


        $isSearchSet = $request->query->get('search');

        $search = $request->query->get('search', null);

        $camposClinicos = $campoClinicoRepository->getAllCamposClinicosByRequest(
            $solicitudId,
            $search,
            false
        );

        //$expediente = $expedienteRepository->getAllExpedientesByRequest($solicitudId);
        $institucion = $institucionRepository->find($id);
        $pagos = $pagoRepository->getAllPagosByRequest($solicitudId);

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
            'search' => $search,
            'pago' => $this->getNormalizePagos($pagos)
        ]);
    }


    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/registrar", name="instituciones#record", methods={"POST", "GET"})
     * @param integer $id
     * @param $solicitudId
     * @param Request $request
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param CarreraRepositoryInterface $carreraRepository
     * @param MontoCarreraManagerInterface $montoCarreraManager
     * @param MontoCarreraRepository $montoCarreraRepository
     * @return Response
     */
    public function recordAction(
        $id,
        $solicitudId,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        CarreraRepositoryInterface $carreraRepository,
        MontoCarreraManagerInterface $montoCarreraManager,
        MontoCarreraRepositoryInterface $montoCarreraRepository
    ) {

        $camposClinicos = $campoClinicoRepository->getAllCamposClinicosByRequest(
            $solicitudId,
            null,
            true
        );

        $carreras = $campoClinicoRepository->getDistinctCarrerasBySolicitud(
            $solicitudId
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


        $montos = $montoCarreraRepository->getAllMontosCarreraByRequest(
            $solicitudId
        );


        if($montos == null){
            foreach ($carreras as $c){
                $montoCarrera = new MontoCarrera();
                $carrera = $carreraRepository->find($c["id"]);
                $montoCarrera->setSolicitud($solicitud);
                $montoCarrera->setCarrera($carrera);
                $solicitud->getMontosCarrera()->add($montoCarrera);
            }
        }

        $form = $this->createForm(SolicitudMontoType::class, $solicitud, [
            'action' => $this->generateUrl('instituciones#record', [
                'id' => $id,
                'solicitudId' => $solicitudId
            ]),
        ]);
    
        if ($request->isMethod('POST')) {
            $inscripciones = $request->get('montosInscripcion');
            $colegiatura = $request->get('montosColegiatura');
            $carrera = $request->get('carreraid');

            $value = 0;
            if($montos != null){
                foreach ($montos as $c){
                    $c->setMontoInscripcion($inscripciones[$value]);
                    $c->setMontoColegiatura($colegiatura[$value]);
                    $result = $montoCarreraManager->Create($c);
                    $value++;
                }    
            }else{
                foreach ($carreras as $c){
                    $montoCarrera = new MontoCarrera();
                    $montoCarrera->setMontoInscripcion($inscripciones[$value]);
                    $montoCarrera->setMontoColegiatura($colegiatura[$value]);
                    $montoCarrera->setSolicitud($solicitud);
                    $carre = $carreraRepository->find($carrera[$value]);
                    $montoCarrera->setCarrera($carre);

                    dump($montoCarrera);

                    $solicitud->getMontosCarrera()->add($montoCarrera);
                    $result = $montoCarreraManager->Create($montoCarrera);
                    $value++;
                }
            }
            

            return new JsonResponse([
                'message' => $result ?
                    "¡La información se actualizado correctamente!" :
                    '¡Ha ocurrido un problema, intenta más tarde!',
                'status' => $result ?
                    Response::HTTP_OK :
                    Response::HTTP_UNPROCESSABLE_ENTITY
            ]);


        }

        return $this->render('institucion_educativa/solicitud/recordAmount.html.twig',[
            'institucion' => $institucion,
            'solicitud' => $this->getNormalizeSolicitud($solicitud),
            'autorizado' => $acc,
            'carreras' => $carreras,
            'montos' => $this->get('serializer')->normalize(
                $solicitud,
                'json',
                [
                    'attributes' => [
                        'montosCarrera' => [
                            'montoInscripcion',
                            'montoColegiatura',
                            'carrera' => [
                                'id',
                                'nombre'
                            ]
                        ]
                    ]
                ])
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
                    'estatus',
                    'noCamposSolicitados',
                    'noCamposAutorizados',
                    'tipoPago'
                ]
            ]);
    }


    /**
     * @param $pagos
     * @return array
     */
    private function getNormalizePagos($pagos)
    {
        return $this->get('serializer')->normalize(
            $pagos,
            'json',
            [
                'attributes' => [
                    'monto',
                    'fechaPago',
                    'comprobantePago',
                    'requiereFactura',
                    'referenciaBancaria',
                    'factura'
                ]
            ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function setFilters(Request $request)
    {
        $isOffsetSet = $request->query->get('offset');
        $isSearchSet = $request->query->get('search');
        $isTipoPagoSet = $request->query->get('tipoPago');
        return array($isOffsetSet, $isSearchSet, $isTipoPagoSet);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function initializeFiltersWithDefaultValues(Request $request)
    {
        $offset = $request->query->getInt('offset', 1);
        $search = $request->query->get('search', null);
        $tipoPago = $request->query->get('tipoPago', null);
        return array($offset, $search, $tipoPago);
    }

    /**
     * @param $isOffsetSet
     * @param $isSearchSet
     * @param $isTipoPagoSet
     * @return bool
     */
    private function isRequestedToFilter($isOffsetSet, $isSearchSet, $isTipoPagoSet)
    {
        return isset($isOffsetSet) || isset($isSearchSet) || isset($isTipoPagoSet);
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
                    'convenio' => [
                        'carrera' => [
                            'id',
                            'nombre',
                            'nivelAcademico' => [
                                'id',
                                'nombre'
                            ]
                        ],
                        'cicloAcademico' => [
                            'nombre'
                        ]
                    ],
                    'solicitud' => [
                        'id',
                        'noSolicitud',
                        'estatus',
                        'documento',
                        'fechaComprobante',
                        'descripcion',
                        'urlArchivo'
                    ],
                    'unidad' => [
                        'nombre'
                    ]
                ]
            ]);
    }

    private function getNormalizeSolicitud($solicitud)
    {

        return $this->get('serializer')->normalize(
            $solicitud,
            'json',
            [
                'attributes' => [
                    'id',
                    'noSolicitud',
                    'estatus',
                    'fecha',
                    'montosCarrera' => [
                        'montoInscripcion',
                        'montoColegiatura',
                        'carrera'
                    ]
                ]
            ]
        );
    }
}
