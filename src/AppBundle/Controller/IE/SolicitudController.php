<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\DTO\IE\InicioDTO;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Event\ReferenciaBancariaZipUnloadedEvent;
use AppBundle\Form\Type\ComprobantePagoType\SolicitudComprobantePagoType;
use AppBundle\Form\Type\FormaPagoType;
use AppBundle\Form\Type\ValidacionMontos\SolicitudValidacionMontosType;
use AppBundle\ObjectValues\SolicitudId;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use AppBundle\Repository\IE\DetalleSolicitud\DetalleSolicitud;
use AppBundle\Repository\IE\SeleccionarFormaPago\ListaCamposClinicosAutorizados\CamposClinicos;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Security\Voter\SolicitudVoter;
use AppBundle\Service\GeneradorReferenciaBancariaZIPInterface;
use AppBundle\Service\ProcesadorFormaPagoInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/ie")
 */
class SolicitudController extends DIEControllerController
{
    private $solicitudRepository;

    public function __construct(SolicitudRepositoryInterface $solicitudRepository)
    {
        $this->solicitudRepository = $solicitudRepository;
    }

    /**
     * @Route("/inicio", name="ie#inicio", methods={"GET"})
     * @IsGranted("ROLE_IE")
     * @param Request $request
     * @param NormalizerInterface $normalizer
     * @return Response
     */
    public function inicioAction(
        Request $request,
        NormalizerInterface $normalizer
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        list($isOffsetSet, $isSearchSet, $isTipoPagoSet, $isPerPageSet) = $this->setFilters($request);
        list($offset, $search, $tipoPago, $perPage) = $this->initializeFiltersWithDefaultValues($request);

        $solicitudes = $this->solicitudRepository->getAllSolicitudesByInstitucion(
            $institucion->getId(),
            $perPage,
            $tipoPago,
            $offset,
            $search
        );

        $collection = new ArrayCollection();
        /** @var Solicitud $solicitud */
        foreach($solicitudes as $solicitud) {
            $collection->add(new InicioDTO($solicitud));
        }

        $totalSolicitudes = round(
            count($solicitudes) / $perPage
        );

        if ($this->isRequestedToFilter($isOffsetSet, $isSearchSet, $isTipoPagoSet, $isPerPageSet)) {
            return new JsonResponse([
                'camposClinicos' => $normalizer->normalize($collection, 'json', [
                    'attributes' => [
                        'id',
                        'estatus',
                        'fecha',
                        'noCamposAutorizados',
                        'noCamposSolicitados',
                        'noSolicitud',
                        'tipoPago',
                        'ultimoPago'
                    ]
                ]),
                'total' => $totalSolicitudes,
                'paginatorTotalPerPage' => $perPage
            ]);
        }

        return $this->render('ie/solicitud/inicio.html.twig', [
            'total' => $totalSolicitudes,
            'paginatorTotalPerPage' => $perPage
        ]);
    }

    /**
     * @Route("/solicitudes/{id}/detalle-de-solicitud", name="ie#detalle_de_solicitud", methods={"GET"})
     * @param integer $id
     * @param DetalleSolicitud $detalleSolicitud
     * @param NormalizerInterface $normalizer
     * @return Response
     */
    public function detalleDeSolicitudAction(
        $id,
        DetalleSolicitud $detalleSolicitud,
        NormalizerInterface $normalizer
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($id);
        if(!$solicitud) throw $this->createNotFindSolicitudException($id);

        $this->denyAccessUnlessGranted(SolicitudVoter::DETALLE_DE_SOLICITUD, $solicitud);

        $solicitud = $detalleSolicitud->detalleBySolicitud(SolicitudId::fromString($solicitud->getId()));

        return $this->render('ie/solicitud/detalle_de_solicitud.html.twig', [
            'solicitud' => $normalizer->normalize($solicitud, 'json')
        ]);
    }

    /**
     * @Route("/solicitudes/{id}/registrar-montos", name="ie#registrar_montos", methods={"POST", "GET"})
     * @Route("/solicitudes/{id}/corregir-montos", name="ie#corregir_montos", methods={"POST", "GET"})
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
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($id);
        if(!$solicitud) throw $this->createNotFindSolicitudException($id);

        $routeName = $request->attributes->get('_route');
        $this->denyAccessUnlessGranted(
            $routeName === 'ie#registrar_montos' ?
                SolicitudVoter::REGISTRAR_MONTOS :
                SolicitudVoter::CORREGIR_MONTOS,
            $solicitud
        );

        if(
            $solicitud->getEstatus() != SolicitudInterface::CONFIRMADA &&
            $solicitud->getEstatus() != SolicitudInterface::MONTOS_INCORRECTOS_CAME
        ) {
            $this->addFlash('danger', 'No puede realizar esta acción en este momento');

            return $this->redirectToRoute('ie#inicio');
        }

        $autorizados = $campoClinicoRepository->getAutorizadosBySolicitud($id);
        $carreras = $campoClinicoRepository->getDistinctCarrerasBySolicitud($id);

        $form = $this->createForm(SolicitudValidacionMontosType::class, $solicitud, [
            'action' => $this->generateUrl("ie#registrar_montos", [
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

            $this->addFlash('success', 'Se han guardado correctamente los montos para la solicitud ' . $solicitud->getNoSolicitud());

            return $this->redirectToRoute('ie#inicio');
        }

        return $this->render('ie/solicitud/registrar_montos.html.twig', [
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
            'errors' => $this->getFormErrors($form)
        ]);
    }

    /**
     * @Route("/solicitudes/{id}/seleccionar-forma-de-pago", name="ie#seleccionar_forma_de_pago")
     * @param int $id
     * @param Request $request
     * @param ProcesadorFormaPagoInterface $procesadorFormaPago
     * @param CamposClinicos $camposClinicos
     * @param NormalizerInterface $normalizer
     * @return Response
     */
    public function seleccionarFormaDePagoAction(
        $id,
        Request $request,
        ProcesadorFormaPagoInterface $procesadorFormaPago,
        CamposClinicos $camposClinicos,
        NormalizerInterface $normalizer
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($id);
        if(!$solicitud) throw $this->createNotFindSolicitudException($id);

        $this->denyAccessUnlessGranted(SolicitudVoter::SELECCIONAR_FORMA_DE_PAGO, $solicitud);

        $form = $this->createForm(FormaPagoType::class, $solicitud, [
            'action' => $this->generateUrl('ie#seleccionar_forma_de_pago', [
                'id' => $id,
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $procesadorFormaPago->procesar($form->getData());

            $this->addFlash(
                'success',
                sprintf('Se ha guardado correctamente la opción por Pago %s para la solicitud %s',
                    $solicitud->getTipoPago(),
                    $solicitud->getNoSolicitud()
                )
            );

            return $this->redirectToRoute('ie#detalle_de_forma_de_pago', [
                'id' => $solicitud->getId()
            ]);
        }

        return $this->render('ie/solicitud/seleccionar_forma_pago.html.twig', [
            'camposClinicos' => $normalizer->normalize(
                $camposClinicos->listaCamposClinicosAutorizados(new SolicitudId($solicitud->getId()))
            ),
            'institucion' => $institucion,
            'solicitud' => $this->getNormalizeSolicitud($solicitud)
        ]);
    }

    /**
     * @Route("/solicitudes/{id}/detalle-de-forma-de-pago", name="ie#detalle_de_forma_de_pago")
     * @param $id
     * @return Response
     */
    public function detalleDeFormaDePago($id)
    {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($id);
        if(!$solicitud) throw $this->createNotFindSolicitudException($id);

        $this->denyAccessUnlessGranted(SolicitudVoter::DETALLE_DE_FORMA_DE_PAGO, $solicitud);

        return $this->render('ie/solicitud/detalle_de_forma_de_pago.html.twig', [
            'institucion' => $institucion,
            'solicitud' => $solicitud
        ]);
    }

    /**
     * @Route("/solicitudes/{id}/descargar-referencias-bancarias", name="ie#descargar_referencias_bancarias")
     * @param $id
     * @param GeneradorReferenciaBancariaZIPInterface $generadorReferenciaBancariaZIP
     * @param EventDispatcherInterface $dispatcher
     */
    public function descargarReferenciasBancarias(
        $id,
        GeneradorReferenciaBancariaZIPInterface $generadorReferenciaBancariaZIP,
        EventDispatcherInterface $dispatcher
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($id);
        if(!$solicitud) throw $this->createNotFindSolicitudException($id);

        $this->denyAccessUnlessGranted(SolicitudVoter::DESCARGAR_REFERENCIAS_BANCARIAS, $solicitud);

        $dispatcher->dispatch(
            ReferenciaBancariaZipUnloadedEvent::NAME,
            new ReferenciaBancariaZipUnloadedEvent($solicitud)
        );

        return $generadorReferenciaBancariaZIP->generarZipResponse($solicitud);
    }


    /**
     * @Route("/solicitudes/{id}/cargar-comprobante", name="ie#cargar_comprobante")
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function cargarComprobanteAction(
        $id,
        Request $request,
        EntityManagerInterface $entityManager
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($id);
        if(!$solicitud) throw $this->createNotFindSolicitudException($id);

        $this->denyAccessUnlessGranted(SolicitudVoter::CARGAR_COMPROBANTE, $solicitud);

        $form = $this->createForm(SolicitudComprobantePagoType::class, $solicitud, [
            'action' => $this->generateUrl('ie#cargar_comprobante', [
                'id' => $id
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

        return $this->render('ie/solicitud/cargar_comprobante.html.twig', [
            'institucion' => $this->getNormalizeInstitucion($institucion),
            'solicitud' => $this->getNormalizeSolicitud($solicitud)
        ]);
    }

    /**
     * @Route("/solicitudes/{id}/correccion-de-pago-fofoe", name="ie#correccion_de_pago_fofoe")
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function correccionDePagoFofeoAction(
        $id,
        Request $request,
        EntityManagerInterface $entityManager
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($id);
        if(!$solicitud) throw $this->createNotFindSolicitudException($id);

        $this->denyAccessUnlessGranted(SolicitudVoter::CORRECCION_DE_PAGO_FOFOE, $solicitud);

        $form = $this->createForm(SolicitudComprobantePagoType::class, $solicitud, [
            'action' => $this->generateUrl('ie#correccion_de_pago_fofoe', [
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

        return $this->render('ie/solicitud/correccion_de_pago_fofeo.html.twig', [
            'institucion' => $this->getNormalizeInstitucion($institucion),
            'solicitud' => $this->getNormalizeSolicitud($solicitud)
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
        $isPerPageSet = $request->query->get('perPage');
        return array($isOffsetSet, $isSearchSet, $isTipoPagoSet, $isPerPageSet);
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
        $perPage = $request->query->get('perPage', 1);
        return array($offset, $search, $tipoPago, $perPage);
    }

    /**
     * @param $isOffsetSet
     * @param $isSearchSet
     * @param $isTipoPagoSet
     * @return bool
     */
    private function isRequestedToFilter($isOffsetSet, $isSearchSet, $isTipoPagoSet, $isPerPageSet)
    {
        return isset($isOffsetSet) || isset($isSearchSet) || isset($isTipoPagoSet) || isset($isPerPageSet);
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
