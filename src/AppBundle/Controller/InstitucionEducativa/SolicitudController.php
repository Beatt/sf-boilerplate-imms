<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Form\Type\FormaPagoType;
use AppBundle\Form\Type\ValidacionMontos\SolicitudValidacionMontosType;
use AppBundle\Normalizer\FormaPagoNormalizer;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\ExpedienteRepositoryInterface;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Service\GeneradorReferenciaBancariaZIPInterface;
use AppBundle\Service\ProcesadorFormaPagoInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

class SolicitudController extends Controller
{
    /**
     * @Route("/instituciones/{id}/solicitudes", methods={"GET"}, name="solicitudes#index")
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
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}", name="solicitudes#show", methods={"GET"})
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
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/registrar", name="solicitudes#record", methods={"POST", "GET"})
     * @param integer $id
     * @param $solicitudId
     * @param Request $request
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function recordAction(
        $id,
        $solicitudId,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        EntityManagerInterface $entityManager
    ) {

        $carreras = $campoClinicoRepository->getDistinctCarrerasBySolicitud($solicitudId);
        $institucion = $institucionRepository->find($id);

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        $form = $this->createForm(SolicitudValidacionMontosType::class, $solicitud, [
            'action' => $this->generateUrl("solicitudes#record", [
                'id' => $id,
                'solicitudId' => $solicitudId,
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $solicitud->setEstatus(SolicitudInterface::EN_VALIDACION_DE_MONTOS_CAME);
            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('success', 'Se ha guardado correctamente los montos');

            return $this->redirectToRoute('solicitudes#index', [
                'id' => $id
            ]);
        }

        return $this->render('institucion_educativa/solicitud/recordAmount.html.twig',[
            'institucion' => $institucion,
            'solicitud' => $this->getNormalizeSolicitud($solicitud),
            'carreras' => $carreras,
            'montos' => $this->get('serializer')->normalize(
                $solicitud,
                'json',
                [
                    'attributes' => [
                        'montosCarrera' => [
                            'montoInscripcion',
                            'montoColegiatura'
                        ]
                    ]
                ]),
        ]);
    }


    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/seleccionar-forma-de-pago", name="solicitudes#seleccionar_forma_de_pago")
     * @param int $id
     * @param int $solicitudId
     * @param Request $request
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param FormaPagoNormalizer $formaPagoNormalizer
     * @param ProcesadorFormaPagoInterface $procesadorFormaPago
     * @return Response
     */
    public function seleccionarFormaDePagoAction(
        $id,
        $solicitudId,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        FormaPagoNormalizer $formaPagoNormalizer,
        ProcesadorFormaPagoInterface $procesadorFormaPago
    ) {
        $institucion = $institucionRepository->find($id);

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        $form = $this->createForm(FormaPagoType::class, $solicitud, [
            'action' => $this->generateUrl('solicitudes#seleccionar_forma_de_pago', [
                'id' => $id,
                'solicitudId' => $solicitudId
            ])
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $solicitud = $form->getData();
            $procesadorFormaPago->procesar($solicitud);

            return $this->redirectToRoute('solicitudes#detalle_forma_de_pago', [
                'id' => $id,
                'solicitudId' => $solicitudId
            ]);
        }

        $camposClinicos = $formaPagoNormalizer->normalizeCamposClinicos(
            $solicitud->getCamposClinicos()
        );

        return $this->render('institucion_educativa/solicitud/seleccionar_forma_pago.html.twig', [
            'solicitud' => $solicitud,
            'institucion' => $institucion,
            'camposClinicos' => $camposClinicos
        ]);
    }

    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/detalle-forma-de-pago", name="solicitudes#detalle_forma_de_pago")
     * @param $id
     * @param $solicitudId
     * @param InstitucionRepositoryInterface $institucionRepository
     * @return Response
     */
    public function detalleFormaDePago(
        $id,
        $solicitudId,
        InstitucionRepositoryInterface $institucionRepository
    ) {
        $institucion = $institucionRepository->find($id);

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        return $this->render('institucion_educativa/solicitud/detalle_forma_pago.html.twig', [
            'institucion' => $institucion,
            'solicitud' => $solicitud
        ]);
    }

    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/descargar-referencias-bancarias", name="solicitudes#descargar_referencias_bancarias")
     * @param $id
     * @param $solicitudId
     * @param GeneradorReferenciaBancariaZIPInterface $generadorReferenciaBancariaZIP
     */
    public function descargarReferenciasBancarias(
        $id,
        $solicitudId,
        GeneradorReferenciaBancariaZIPInterface $generadorReferenciaBancariaZIP
    ) {
        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        return $generadorReferenciaBancariaZIP->generarZipResponse($solicitud);
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
                    'factura',
                    'referenciaBancaria',
                    'facturas'
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
                        'montoColegiatura'
                    ]
                ]
            ]
        );
    }
}
