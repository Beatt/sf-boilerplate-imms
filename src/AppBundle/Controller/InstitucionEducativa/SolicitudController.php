<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Form\Type\ComprobantePagoType\SolicitudComprobantePagoType;
use AppBundle\Form\Type\ValidacionMontos\SolicitudValidacionMontosType;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\ExpedienteRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Service\GeneradorReferenciaBancariaZIPInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ie")
 */
class SolicitudController extends DIEControllerController
{
    /**
     * @Route("/inicio", name="ie#inicio", methods={"GET"})
     * @param Request $request
     * @param SolicitudRepositoryInterface $solicitudRepository
     * @return Response
     */
    public function inicioAction(
        Request $request,
        SolicitudRepositoryInterface $solicitudRepository
    )
    {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();

        list($isOffsetSet, $isSearchSet, $isTipoPagoSet) = $this->setFilters($request);
        list($offset, $search, $tipoPago) = $this->initializeFiltersWithDefaultValues($request);

        $camposClinicos = $solicitudRepository->getAllSolicitudesByInstitucion(
            $institucion->getId(),
            $tipoPago,
            $offset,
            $search
        );

        if ($this->isRequestedToFilter($isOffsetSet, $isSearchSet, $isTipoPagoSet)) {
            return new JsonResponse([
                'camposClinicos' => $this->getNormalizeSolicitudes($camposClinicos),
                'total' => round(count($camposClinicos) / SolicitudRepositoryInterface::PAGINATOR_PER_PAGE)
            ]);

        }

        return $this->render('institucion_educativa/solicitud/inicio.html.twig', [
            'institucion' => $institucion,
            'total' => round(count($camposClinicos) / SolicitudRepositoryInterface::PAGINATOR_PER_PAGE)
        ]);
    }

    /**
     * @Route("/solicitudes/{id}/detalle-de-solicitud", name="ie#detalle_de_solicitud", methods={"GET"})
     * @param integer $id
     * @param Request $request
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param ExpedienteRepositoryInterface $expedienteRepository
     * @param PagoRepositoryInterface $pagoRepository
     * @return Response
     */
    public function detalleDeSolicitudAction(
        $id,
        Request $request,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        ExpedienteRepositoryInterface $expedienteRepository,
        PagoRepositoryInterface $pagoRepository
    )
    {


        $isSearchSet = $request->query->get('search');

        $search = $request->query->get('search', null);

        $camposClinicos = $campoClinicoRepository->getAllCamposClinicosByRequest(
            $id,
            $search,
            false
        );

        //$expediente = $expedienteRepository->getAllExpedientesByRequest($id);
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        $pagos = $pagoRepository->getAllPagosByRequest($id);

        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($id);

        $totalCampos = count($camposClinicos);

        $acc = 0;

        foreach ($camposClinicos as $campoClinico) {
            if ($campoClinico->getLugaresAutorizados() > 0) {
                $acc++;
            }
        }

        if (
        isset($isSearchSet)
        ) {
            return new JsonResponse([
                'totalCampos' => $totalCampos,
                'autorizado' => $acc,
                'camposClinicos' => $this->getNormalizeCamposClinicos($camposClinicos)
            ]);

        }

        return $this->render('institucion_educativa/solicitud/detalle_de_solicitud.html.twig', [
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
     * @Route("/solicitudes/{id}/registrar-montos", name="ie#registrar-montos", methods={"POST", "GET"})
     * @Route("/solicitudes/{id}/corregir-montos", name="ie#corregir-montos", methods={"POST", "GET"})
     * @param integer $id
     * @param Request $request
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function registrarMontosAction(
        $id,
        Request $request,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        EntityManagerInterface $entityManager
    )
    {
        $routeName = $request->attributes->get('_route');
        $carreras = $campoClinicoRepository->getDistinctCarrerasBySolicitud($id);
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        $autorizados = $campoClinicoRepository->getAutorizadosBySolicitud($id);

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($id);

        $form = $this->createForm(SolicitudValidacionMontosType::class, $solicitud, [
            'action' => $this->generateUrl("ie#registrar-montos", [
                'id' => $id,
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $solicitud->setEstatus(SolicitudInterface::EN_VALIDACION_DE_MONTOS_CAME);
            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('success', 'Se ha guardado correctamente los montos');

            return $this->redirectToRoute('ie#inicio', [
                'id' => $id
            ]);
        }

        return $this->render('institucion_educativa/solicitud/registrar_montos.html.twig', [
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
                            'montoColegiatura',
                            'carrera' => [
                                'id',
                                'nombre'
                            ]
                        ]
                    ]
                ]),
        ]);
    }

    /**
     * @Route("/solicitudes/{id}/seleccionar-forma-de-pago", name="ie#seleccionar_forma_de_pago)
     * @param int $id
     * @param Request $request
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function seleccionarFormaDePagoAction(
        $id,
        Request $request,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        EntityManagerInterface $entityManager
    )
    {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($id);

        $form = $this->createForm(SolicitudComprobantePagoType::class, $solicitud, [
            'action' => $this->generateUrl('solicitudes#seleccionar_forma_de_pago', [
                'id' => $id,
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $solicitud->setEstatus(SolicitudInterface::EN_VALIDACION_FOFOE);
            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('success', 'Se ha guardado correctamente los montos');

            return $this->redirectToRoute('ie#inicio');
        }

        return $this->render('institucion_educativa/solicitud/payment.html.twig', [
            'institucion' => $institucion,
            'solicitud' => $this->getNormalizeSolicitud($solicitud)
        ]);
    }

    /**
     * @Route("/solicitudes/{id}/detalle-de-forma-de-pago", name="ie#detalle_de_forma_de_pago")
     * @param $id
     * @return Response
     */
    public function detalleFormaDePago($id)
    {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($id);

        return $this->render('institucion_educativa/solicitud/detalle_de_forma_de_pago.html.twig', [
            'institucion' => $institucion,
            'solicitud' => $solicitud
        ]);
    }

    /**
     * @Route("/solicitudes/{id}/descargar-referencias-bancarias", name="ie#descargar_referencias_bancarias")
     * @param $id
     * @param GeneradorReferenciaBancariaZIPInterface $generadorReferenciaBancariaZIP
     */
    public function descargarReferenciasBancarias(
        $id,
        GeneradorReferenciaBancariaZIPInterface $generadorReferenciaBancariaZIP
    ) {
        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($id);

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
                    ],
                    'observaciones',
                    'referenciaBancaria',
                    'monto'
                ]
            ]
        );
    }
}
