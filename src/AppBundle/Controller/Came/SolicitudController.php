<?php

namespace AppBundle\Controller\Came;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Convenio;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\Unidad;
use AppBundle\Form\Type\SolicitudType;
use AppBundle\Form\Type\ValidaSolicitudType;
use AppBundle\Service\SolicitudManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

class SolicitudController extends DIEControllerController
{
    const DEFAULT_PERPAGE = 10;
    /**
     * @Route("/came/solicitud", methods={"GET"}, name="came.solicitud.index")
     */
    public function indexAction(Request $request)
    {
        $perPage = $request->query->get('perPage', self::DEFAULT_PERPAGE);
        $page = $request->query->get('page', 1);
        $delegacion = $this->getUserDelegacionId();
        $unidad = $this->getUserUnidadId();
        if (is_null($delegacion) && is_null($unidad)) {
            throw $this->createAccessDeniedException();
        }
        $solicitudes =
        $unidad ?
          $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->getAllSolicitudesByUnidad($unidad, $perPage, $page, $request->query->all())
          : // $delegacion
          $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->getAllSolicitudesByDelegacion($delegacion, $perPage, $page, $request->query->all());
        return $this->render('came/solicitud/index.html.twig', [
            'solicitudes' => $this->get('serializer')->normalize(
                $solicitudes['data'],
                'json',
                [
                    'attributes' => [
                        'id',
                        'fecha',
                        'estatus',
                        'noSolicitud',
                        'estatus',
                        'estatusCameFormatted',
                        'institucion' => ['id', 'nombre'],
                        'camposClinicosSolicitados',
                        'camposClinicosAutorizados',
                    ]
                ]
            ),
            'meta' => ['total' => $solicitudes['total'], 'perPage' => $perPage, 'page' => $page]
        ]);
    }

    /**
     * @Route("/came/api/solicitud", methods={"GET"}, name="solicitud.index.json")
     */
    public function indexApiAction(Request $request)
    {
        $perPage = $request->query->get('perPage', self::DEFAULT_PERPAGE);
        $page = $request->query->get('page', 1);
        $delegacion = $this->getUserDelegacionId();
        $unidad = $this->getUserUnidadId();
        if (is_null($delegacion) && is_null($unidad)) {
            throw $this->createAccessDeniedException();
        }
        $solicitudes =
        $unidad ?
          $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->getAllSolicitudesByUnidad($unidad, $perPage, $page, $request->query->all())
        :  $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->getAllSolicitudesByDelegacion($delegacion, $perPage, $page, $request->query->all());
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize(
                $solicitudes['data'],
                'json',
                [
                    'attributes' => [
                        'id',
                        'fecha',
                        'estatus',
                        'noSolicitud',
                        'estatus',
                        'estatusCameFormatted',
                        'institucion' => ['id', 'nombre'],
                        'camposClinicosSolicitados',
                        'camposClinicosAutorizados',
                    ]
                ]
            ),
            'meta' => ['total' => $solicitudes['total'], 'perPage' => $perPage, 'page' => $page]
        ]);
    }

    /**
     * @Route("/came/solicitud/create", methods={"GET"}, name="solicitud.create")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(SolicitudType::class);
        $this->getUser();
        $delegacion = $this->getUserDelegacionId();
        $unidad = $this->getUserUnidadId();
        if (is_null($delegacion) && is_null($unidad)) {
            throw $this->createAccessDeniedException();
        }
        $instituciones = null;
        $unidades = null;
        if ($unidad) {
          $unidadE = $this->getDoctrine()
            ->getRepository(Unidad::class)
            ->findOneBy(['id' => $unidad]);
          $unidades = [$unidadE];
          $instituciones = $unidadE ?
            $this->getDoctrine()
              ->getRepository(Institucion::class)
              ->findAllPrivate($unidadE->getDelegacion()->getId())
          : null;

        } else { // $delegacion
          $instituciones = $this->getDoctrine()
            ->getRepository(Institucion::class)
            ->findAllPrivate($delegacion);
          $unidades = $this->getDoctrine()
            ->getRepository(Unidad::class)
            ->getAllUnidadesByDelegacion($delegacion);
        }
        return $this->render('came/solicitud/create.html.twig', [
            'form' => $form->createView(),
            'instituciones' => $this->get('serializer')->normalize($instituciones, 'json',
                ['attributes' => ['id', 'nombre', 'rfc', 'direccion', 'telefono', 'extension', 'correo', 'sitioWeb', 'fax', 'representante']]),
            'unidades' => $this->get('serializer')->normalize($unidades, 'json',
                ['attributes' => ['id', 'nombre', 'claveUnidad']])
        ]);
    }


    /**
     * @Route("/came/api/solicitud", methods={"POST"}, name="solicitud.store")
     * @param Request $request
     * @param SolicitudManagerInterface $solicitudManager
     */
    public function storeAction(Request $request, SolicitudManagerInterface $solicitudManager)
    {
        $result = $solicitudManager->create(new Solicitud());
        return $this->jsonResponse($result);
    }

    /**
     * @Route("/came/solicitud/{id}/edit", methods={"GET"}, name="solicitud.edit", requirements={"id"="\d+"})
     * @param Request $request
     */
    public function editAction(Request $request, $id)
    {
        $delegacion = $this->getUserDelegacionId();
        $unidad = $this->getUserUnidadId();
        if (is_null($delegacion) && is_null($unidad)) {
            throw $this->createAccessDeniedException();
        }
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            $this->addFlash('danger', 'No existe la solicitud indicada');
            return $this->redirectToRoute('came.solicitud.index');
        }
        if (!$this->isGrantedUserAccessToSolicitud($solicitud)) {
            $this->addFlash('danger', 'No puedes modificar una solicitud de otra '
              .($unidad ? 'unidad':'delegación')
            );
            return $this->redirectToRoute('came.solicitud.index');
        }
        if (!in_array($solicitud->getEstatus(), [Solicitud::CREADA])) {
            $this->addFlash('danger', 'No puedes modificar la solicitud ' . $solicitud->getNoSolicitud());
            return $this->redirectToRoute('came.solicitud.index');
        }
        $instituciones = null;
        $unidades = null;
        if ($unidad) {
          $unidadE = $this->getDoctrine()
            ->getRepository(Unidad::class)
            ->findOneBy(['id' => $unidad]);
          $unidades = [$unidadE];
          $instituciones = $unidadE ?
            $this->getDoctrine()
              ->getRepository(Institucion::class)
              ->findAllPrivate($unidadE->getDelegacion()->getId())
            : null;

        } else { // $delegacion
          $instituciones = $this->getDoctrine()
            ->getRepository(Institucion::class)
            ->findAllPrivate($delegacion);
          $unidades = $this->getDoctrine()
            ->getRepository(Unidad::class)
            ->getAllUnidadesByDelegacion($delegacion);
        }
        $form = $this->createForm(SolicitudType::class);
        return $this->render('came/solicitud/edit.html.twig', [
            'form' => $form->createView(),
            'instituciones' => $this->get('serializer')->normalize($instituciones,
                'json',
                ['attributes' => ['id', 'nombre', 'rfc', 'direccion', 'telefono', 'extension','correo', 'sitioWeb', 'fax', 'representante']]),
            'solicitud' => $this->get('serializer')->normalize($solicitud, 'json',
                ['attributes' => ['id', 'campoClinicos' => ['id', 'asignatura', 'promocion',
                    'convenio' => ['cicloAcademico' => ['id', 'nombre'],
                        'id', 'vigencia', 'vigenciaFormatted', 'label', 'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']]],
                    'lugaresSolicitados', 'lugaresAutorizados', 'horario', 'unidad' => ['id', 'nombre'],
                    'fechaInicial', 'fechaFinal', 'fechaInicialFormatted', 'fechaFinalFormatted'], 'institucion' => ['id', 'nombre', 'fax',
                    'telefono', 'extension', 'correo', 'sitioWeb', 'direccion', 'rfc', 'representante', 'convenios' => ['id', 'nombre',
                        'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']],
                        'cicloAcademico' => ['id', 'nombre'], 'vigencia', 'vigenciaFormatted', 'label']]
                ]]),
            'unidades' => $this->get('serializer')->normalize($unidades, 'json',
                ['attributes' => ['id', 'nombre']]),
        ]);
    }

    /**
     * @Route("/came/api/solicitud/{id}", methods={"PUT"}, name="solicitud.update", requirements={"id"="\d+"})
     * @param Request $request
     * @param SolicitudManagerInterface $solicitudManager
     */
    public function updateAction(Request $request, SolicitudManagerInterface $solicitudManager, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);
        if (!$solicitud) {
            return $this->httpErrorResponse('Not Found', Response::HTTP_NOT_FOUND);
        }
        if (!$this->isGrantedUserAccessToSolicitud($solicitud)) {
            return $this->httpErrorResponse();
        }
        if (!in_array($solicitud->getEstatus(), [Solicitud::CREADA])) {
            return $this->httpErrorResponse('No puedes modificar la solicitud ' . $solicitud->getNoSolicitud());
        }
        $form = $this->createForm(SolicitudType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $solicitudManager->update($form->getData());
            return $this->jsonResponse($result);
        }
        return $this->jsonErrorResponse($form);
    }

    /**
     * @Route("/came/solicitud/{id}", methods={"GET"}, name="solicitud.show", requirements={"id"="\d+"})
     */
    public function showAction($id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            $this->addFlash('danger', 'No existe la solicitud indicada');
            return $this->redirectToRoute('came.solicitud.index');
        }

        if (!$this->isGrantedUserAccessToSolicitud($solicitud)) {
            $this->addFlash('danger', 'No puedes ver una solicitud de otra delegación');
            return $this->redirectToRoute('came.solicitud.index');
        }

        $convenios = $this->getDoctrine()
            ->getRepository(Convenio::class)
            ->getAllBySolicitud($solicitud->getId());

        return $this->render('came/solicitud/show.html.twig', [
            'solicitud' => $this->get('serializer')->normalize(
                $solicitud, 'json', ['attributes' => [
                'id', 'noSolicitud', 'estatusCameFormatted', 'tipoPago', 'fechaComprobanteFormatted',
                'fechaComprobante', 'estatus',
                'institucion' => ['id', 'nombre'],
                'camposClinicosSolicitados', 'camposClinicosAutorizados',
                'campoClinicos' => ['id', 'asignatura', 'promocion',
                    'convenio' => ['cicloAcademico' => ['id', 'nombre'],
                        'id', 'vigencia', 'vigenciaFormatted', 'label', 'carrera' => ['id', 'nombre',
                            'nivelAcademico' => ['id', 'nombre']], 'numero'],
                    'lugaresSolicitados', 'lugaresAutorizados', 'horario', 'unidad' => ['id', 'nombre'],
                    'fechaInicial', 'fechaFinal', 'referenciaBancaria', 'fechaInicialFormatted', 'fechaFinalFormatted',
                    'estatus' => ['id', 'nombre']],
                'pago' => ['id', 'comprobantePago', 'fechaPago', 'fechaPagoFormatted', 'referenciaBancaria', 'factura' => ['fechaFacturacion', 'id', 'fechaFacturacionFormatted']],
                'pagos' => ['id', 'comprobantePago', 'fechaPago', 'fechaPagoFormatted', 'referenciaBancaria', 'factura' => ['fechaFacturacion', 'id', 'fechaFacturacionFormatted']]]
            ]),
            'convenios' => $this->get('serializer')->normalize($convenios, 'json', ['attributes' => [
                'cicloAcademico' => ['id', 'nombre'],
                'id', 'vigencia', 'vigenciaFormatted', 'label',
                'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']]]
            ])
        ]);
    }

    /**
     * @Route("/came/api/solicitud/{id}", methods={"DELETE"}, name="solicitud.delete", requirements={"id"="\d+"})
     */
    public function deleteAction($id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            return $this->httpErrorResponse('Not Found', Response::HTTP_NOT_FOUND);
        }
        if (!$this->isGrantedUserAccessToSolicitud($solicitud)) {
            return $this->httpErrorResponse();
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($solicitud);
        $response = new JsonResponse(['status' => true, "message" => "Solicitud Eliminada con éxito"]);
        return $response;
    }

    /**
     * @Route("/came/api/solicitud/terminar/{id}", methods={"POST"}, name="solicitud.terminar", requirements={"id"="\d+"})
     * @param Request $request
     * @param SolicitudManagerInterface $solicitudManager
     * @param $id
     */
    public function terminarAction(Request $request, SolicitudManagerInterface $solicitudManager, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            return $this->httpErrorResponse('Not Found', Response::HTTP_NOT_FOUND);
        }
        if (!$this->isGrantedUserAccessToSolicitud($solicitud)) {
            return $this->httpErrorResponse();
        }
        if (!in_array($solicitud->getEstatus(), [Solicitud::CREADA])) {
            return $this->httpErrorResponse('Solicitud can be finished only if has status "CREADA"');
        }
        $solicitudManager->finalizar($solicitud, $this->getUser());
        $this->addFlash('success', "Se ha procesado la solicitud {$solicitud->getNoSolicitud()} con éxito");
        return $this->jsonResponse(['status' => true]);
    }

    /**
     * @Route("/came/api/solicitud/validar_montos/{id}", methods={"POST"}, name="solicitud.store_validar_montos", requirements={"id"="\d+"})
     * @param Request $request
     * @param SolicitudManagerInterface $solicitudManager
     * @param $id
     */
    public function validarMontosStoreAction(Request $request, SolicitudManagerInterface $solicitudManager, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            return $this->httpErrorResponse('Not Found', Response::HTTP_NOT_FOUND);
        }
        if (!$this->isGrantedUserAccessToSolicitud($solicitud)) {
            return $this->httpErrorResponse();
        }
        if (!in_array($solicitud->getEstatus(), [Solicitud::EN_VALIDACION_DE_MONTOS_CAME])) {
            return $this->httpErrorResponse('Solicitud can be finished only if has status "EN_VALIDACION_DE_MONTOS_CAME"');
        }
        $form = $this->createForm(ValidaSolicitudType::class, $solicitud);
        $form->get('montos_pagos')->setData($solicitud->getMontosCarreras());
        $form->handleRequest($request);

        $originalDescuentos = array();
        foreach ($solicitud->getMontosCarreras() as $monto) {
            $originalDescuentos[$monto->getId()] = array();
            foreach ($monto->getDescuentos() as $descuento) {
                if ($descuento->getId())
                    $originalDescuentos[$monto->getId()][$descuento->getId()] = $descuento;
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $solicitudManager->validarMontos($form->getData(),
                $form->get('montos_pagos')->getData(),
                isset($request->request->get('solicitud')['validado']),
                $this->getUser(), $originalDescuentos
            );
            if ($result['status']) {
                $this->addFlash('success', "Se ha procesado la validación de montos de la solicitud {$solicitud->getNoSolicitud()} con éxito");
            }
            return $this->jsonResponse($result);
        }
        return $this->jsonErrorResponse($form);
    }

    /**
     * @Route("/came/solicitud/{id}/validar_montos", methods={"GET"}, name="solicitud.validar_montos", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     */
    public function validarMontosAction(Request $request, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            $this->addFlash('danger', 'No existe la solicitud indicada');
            return $this->redirectToRoute('came.solicitud.index');
        }

        if (!$this->isGrantedUserAccessToSolicitud($solicitud)) {
            $this->addFlash('danger', 'No puedes modificar una solicitud de otra delegación');
            return $this->redirectToRoute('came.solicitud.index');
        }

        if (!in_array($solicitud->getEstatus(), [Solicitud::EN_VALIDACION_DE_MONTOS_CAME])) {
            $this->addFlash('danger', 'No es posible validar montos de la solicitud indicada');
            return $this->redirectToRoute('came.solicitud.index');
        }

        $form = $this->createForm(ValidaSolicitudType::class);
        $form->get('montos_pagos')->setData($solicitud->getMontosCarreras());

        return $this->render('came/solicitud/valida_montos.html.twig', [
            'form' => $form->createView(),
            'solicitud' => $this->get('serializer')->normalize(
                $solicitud, 'json', ['attributes' => [
                    'id', 'noSolicitud',
                    'estatusCameFormatted',
                    'documento', 'urlArchivo',
                    'institucion' => ['id', 'nombre'],
                    'camposClinicos' => ['lugaresAutorizados', 'carrera' => ['id']],
                    'montosCarreras' =>
                        ['id', 'montoInscripcion', 'montoColegiatura',
                        'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']],
                        'descuentos' => ['numAlumnos', 'descuentoInscripcion', 'descuentoColegiatura']
                        ]
                ]]
            )
        ]);
    }

    /**
     * @Route("/came/solicitud/{solicitud_id}/oficio", methods={"GET"}, name="came.solicitud.oficio_montos", requirements={"id"="\d+"})
     * @param $solicitud_id
     * @return mixed
     */
    public function downloadOficioMontosAction($solicitud_id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($solicitud_id);


        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $solicitud_id
            );
        }

        $downloadHandler = $this->get('vich_uploader.download_handler');
        return $downloadHandler->downloadObject($solicitud, 'urlArchivoFile');
    }

    /**
     * @Route("/came/solicitud/{solicitud_id}/email/montos_invalidos", methods={"GET"}, name="solicitud.email.montos_invalidos")
     * @param Request $request
     * @param $solicitud_id
     */
    public function showMailTemplateAction($solicitud_id = 1)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($solicitud_id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $solicitud_id
            );
        }
        return $this->render('emails/came/montos_invalidos.html.twig', ['solicitud' => $solicitud, 'came' => $this->getUser()]);
    }

    /**
     * @Route("/came/solicitud/{solicitud_id}/email/bienvenida", methods={"GET"}, name="solicitud.email.bienvenida")
     * @param Request $request
     * @param $solicitud_id
     */
    public function showMailBienvenidaTemplateAction($solicitud_id = 1)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($solicitud_id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $solicitud_id
            );
        }
        return $this->render('emails/came/institucion_bienvenida.html.twig', ['solicitud' => $solicitud, 'password' => '', 'came' => $this->getUser()]);
    }
}
