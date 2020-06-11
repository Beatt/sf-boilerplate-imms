<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Factura;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Form\Type\ComprobantePagoType\SolicitudComprobantePagoType;
use AppBundle\Form\Type\FacturaType\FacturaType;
use AppBundle\Form\Type\FacturaType\SolicitudFacturaType;
use AppBundle\Form\Type\ValidacionMontos\SolicitudValidacionMontosType;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\ExpedienteRepositoryInterface;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Service\GeneradorReferenciaBancariaZIPInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SolicitudController extends DIEControllerController
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
    )
    {
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

        if ($this->isRequestedToFilter($isOffsetSet, $isSearchSet, $isTipoPagoSet)) {
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
    )
    {


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

        return $this->render('institucion_educativa/solicitud/show.html.twig', [
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
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/editar", name="solicitudes#recordEdit", methods={"POST", "GET"})
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
    )
    {
        $routeName = $request->attributes->get('_route');
        $carreras = $campoClinicoRepository->getDistinctCarrerasBySolicitud($solicitudId);
        $institucion = $institucionRepository->find($id);
        $autorizados = $campoClinicoRepository->getAutorizadosBySolicitud($solicitudId);

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
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $solicitud->setEstatus(SolicitudInterface::EN_VALIDACION_DE_MONTOS_CAME);
            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('success', 'Se ha guardado correctamente los montos');

            return $this->redirectToRoute('solicitudes#index', [
                'id' => $id
            ]);
        }


        return $this->render('institucion_educativa/solicitud/recordAmount.html.twig', [
            'institucion' => $institucion,
            'solicitud' => $this->getNormalizeSolicitud($solicitud),
            'carreras' => $carreras,
            'route' => $routeName,
            'autorizados' => $autorizados,
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
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/seleccionar-forma-de-pago", name="solicitudes#seleccionar_forma_de_pago")
     * @param int $id
     * @param int $solicitudId
     * @param Request $request
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function seleccionarFormaDePagoAction(
        $id,
        $solicitudId,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        EntityManagerInterface $entityManager
    )
    {

        $institucion = $institucionRepository->find($id);

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        $form = $this->createForm(SolicitudComprobantePagoType::class, $solicitud, [
            'action' => $this->generateUrl('solicitudes#seleccionar_forma_de_pago', [
                'id' => $id,
                'solicitudId' => $solicitudId,
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

            return $this->redirectToRoute('solicitudes#index', [
                'id' => $id
            ]);
        }

        return $this->render('institucion_educativa/solicitud/payment.html.twig', [
            'institucion' => $institucion,
            'solicitud' => $this->getNormalizeSolicitud($solicitud)
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
    )
    {
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
    )
    {
        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        return $generadorReferenciaBancariaZIP->generarZipResponse($solicitud);
    }

    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/registrarPago", name="solicitudes#recordPayment")
     * @param int $id
     * @param int $solicitudId
     * @param Request $request
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function paymentAction(
        $id,
        $solicitudId,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        EntityManagerInterface $entityManager
    )
    {
        $user = $this->getUser();

        $institucion = $institucionRepository->find($id);

        if($user->getId() != $institucion->getUsuario()->getId()){
            return $this->redirectToRoute('solicitudes#index', [
                'id' => $id
            ]);
        }

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        $form = $this->createForm(SolicitudComprobantePagoType::class, $solicitud, [
            'action' => $this->generateUrl('solicitudes#recordPayment', [
                'id' => $id,
                'solicitudId' => $solicitudId,
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

            /*return $this->redirectToRoute('solicitudes#index', [
                'id' => $id
            ]);*/
            dump($data);
        }

        return $this->render('institucion_educativa/solicitud/payment.html.twig', [
            'institucion' => $this->getNormalizeInstitucion($institucion),
            'solicitud' => $this->getNormalizeSolicitud($solicitud)
        ]);
    }

    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/editarPago", name="solicitudes#editPayment")
     * @param int $id
     * @param int $solicitudId
     * @param Request $request
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function editPaymentAction(
        $id,
        $solicitudId,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        EntityManagerInterface $entityManager
    )
    {

        $institucion = $institucionRepository->find($id);

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        $form = $this->createForm(SolicitudComprobantePagoType::class, $solicitud, [
            'action' => $this->generateUrl('solicitudes#editPayment', [
                'id' => $id,
                'solicitudId' => $solicitudId,
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

            return $this->redirectToRoute('solicitudes#index', [
                'id' => $id
            ]);
        }

        return $this->render('institucion_educativa/solicitud/editPayment.html.twig', [
            'institucion' => $this->getNormalizeInstitucion($institucion),
            'solicitud' => $this->getNormalizeSolicitud($solicitud)
        ]);
    }

    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}/registrarFactura", name="solicitudes#addInvoice")
     * @param int $id
     * @param int $solicitudId
     * @param Request $request
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function addInvoiceAction(
        $id,
        $solicitudId,
        Request $request,
        InstitucionRepositoryInterface $institucionRepository,
        CampoClinicoRepositoryInterface $campoClinicoRepository,
        EntityManagerInterface $entityManager
    )
    {

        $institucion = $institucionRepository->find($id);

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($solicitudId);

        $factura = new Factura();
        $form = $this->createForm(FacturaType::class, $factura, [
            'action' => $this->generateUrl('solicitudes#addInvoice', [
                'id' => $id,
                'solicitudId' => $solicitudId,
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('success', 'Se ha guardado correctamente la factura');

            return $this->redirectToRoute('solicitudes#index', [
                'id' => $id
            ]);
        }
        
        return $this->render('institucion_educativa/solicitud/addInvoice.html.twig', [
            'institucion' => $this->getNormalizeInstitucion($institucion),
            'solicitud' => $this->getNormalizeSolicitud($solicitud)
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
                    ],
                    'observaciones',
                    'referenciaBancaria',
                    'monto',
                    'pagos' => [
                        'id',
                        'monto',
                        'fechaPago',
                        'comprobantePago',
                        'requiereFactura'
                    ]
                ]
            ]
        );
    }

    private function getNormalizeInstitucion($institucion)
    {

        return $this->get('serializer')->normalize(
            $institucion,
            'json',
            [
                'attributes' => [
                    'id',
                    'nombre',
                    'rfc'
                ]
            ]
        );
    }
}
