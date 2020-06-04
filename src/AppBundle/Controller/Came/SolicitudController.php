<?php

namespace AppBundle\Controller\Came;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Convenio;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\Unidad;
use AppBundle\Form\Type\SolicitudType;
use AppBundle\Form\Type\ValidaSolicitudType;
use AppBundle\Service\SolicitudManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SolicitudController extends DIEControllerController
{
    /**
     * @Route("/solicitud", methods={"GET"}, name="solicitud.index")
     * @Security("has_role('ROLE_CONSULTAR_SOLICITUDES')")
     */
    public function indexAction(Request $request)
    {
        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $delegacion = $this->getUserDelegacionId($request->query->get('delegacion'));
        if(is_null($delegacion)){
            throw $this->createAccessDeniedException();
        }
        $solicitudes = $this->getDoctrine()
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
     * @Route("/api/solicitud", methods={"GET"}, name="solicitud.index.json")
     * @Security("has_role('ROLE_CONSULTAR_SOLICITUDES')")
     */
    public function indexApiAction(Request $request)
    {
        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $delegacion = $this->getUserDelegacionId($request->query->get('delegacion'));
        if(is_null($delegacion)){
            throw $this->createAccessDeniedException();
        }
        $solicitudes = $this->getDoctrine()
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
     * @Route("/solicitud/create", methods={"GET"}, name="solicitud.create")
     * @Security("has_role('ROLE_AGREGAR_SOLICITUD')")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(SolicitudType::class);
        $this->getUser();
        $delegacion = $this->getUserDelegacionId($request->query->get('delegacion'));
        if(is_null($delegacion)){
            throw $this->createAccessDeniedException();
        }
        $instituciones = $this->getDoctrine()
            ->getRepository(Institucion::class)
            ->findAllPrivate($delegacion);
        $unidades = $this->getDoctrine()
            ->getRepository(Unidad::class)
            ->getAllUnidadesByDelegacion($delegacion);
        return $this->render('came/solicitud/create.html.twig', [
            'form' => $form->createView(),
            'instituciones' => $this->get('serializer')->normalize($instituciones, 'json',
                ['attributes' => ['id', 'nombre', 'rfc', 'direccion', 'telefono', 'correo', 'sitioWeb', 'fax', 'representante']]),
            'unidades' => $this->get('serializer')->normalize($unidades, 'json',
                ['attributes' => ['id', 'nombre']])
        ]);
    }


    /**
     * @Route("/api/solicitud", methods={"POST"}, name="solicitud.store")
     * @Security("has_role('ROLE_AGREGAR_SOLICITUD')")
     * @param Request $request
     * @param SolicitudManagerInterface $solicitudManager
     */
    public function storeAction(Request $request, SolicitudManagerInterface $solicitudManager)
    {
        $result = $solicitudManager->create(new Solicitud());
        return $this->jsonResponse($result);
    }

    /**
     * @Route("/solicitud/{id}/edit", methods={"GET"}, name="solicitud.edit")
     * @param Request $request
     * @Security("has_role('ROLE_EDITAR_SOLICITUD')")
     */
    public function editAction(Request $request, $id)
    {
        $delegacion = $this->getUserDelegacionId($request->query->get('delegacion'));
        if(is_null($delegacion)){
            throw $this->createAccessDeniedException();
        }
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }
        if(!$this->validarSolicitudDelegacion($solicitud)){
            throw $this->createAccessDeniedException();
        }
        $instituciones = $this->getDoctrine()
            ->getRepository(Institucion::class)
            ->findAllPrivate($delegacion);
        $unidades = $this->getDoctrine()
            ->getRepository(Unidad::class)
            ->getAllUnidadesByDelegacion($delegacion);
        $form = $this->createForm(SolicitudType::class);
        return $this->render('came/solicitud/edit.html.twig', [
            'form' => $form->createView(),
            'instituciones' => $this->get('serializer')->normalize($instituciones,
                'json',
                ['attributes' => ['id', 'nombre', 'rfc', 'direccion', 'telefono', 'correo', 'sitioWeb', 'fax', 'representante']]),
            'solicitud' => $this->get('serializer')->normalize($solicitud, 'json',
                ['attributes' => ['id', 'campoClinicos' => ['id',
                    'convenio' => ['cicloAcademico' => ['id', 'nombre'],
                        'id', 'vigencia', 'label', 'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']]],
                    'lugaresSolicitados', 'lugaresAutorizados', 'horario', 'unidad' => ['id', 'nombre'],
                    'fechaInicial', 'fechaFinal'], 'institucion' => ['id', 'nombre', 'fax',
                    'telefono', 'correo', 'sitioWeb', 'direccion', 'rfc', 'representante', 'convenios' => ['id', 'nombre', 'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']],
                        'cicloAcademico' => ['id', 'nombre'], 'vigencia', 'label']]
                ]]),
            'unidades' => $this->get('serializer')->normalize($unidades, 'json',
                ['attributes' => ['id', 'nombre']]),
        ]);
    }

    /**
     * @Route("/api/solicitud/{id}", methods={"PUT"}, name="solicitud.update")
     * @Security("has_role('ROLE_EDITAR_SOLICITUD')")
     * @param Request $request
     * @param SolicitudManagerInterface $solicitudManager
     */
    public function updateAction(Request $request, SolicitudManagerInterface $solicitudManager, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);
        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }
        if(!$this->validarSolicitudDelegacion($solicitud)){
            throw $this->createAccessDeniedException();
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
     * @Route("/solicitud/{id}", methods={"GET"}, name="solicitud.show")
     * @Security("has_role('ROLE_DETALLE_SOLICITUD')")
     */
    public function showAction($id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }

        if(!$this->validarSolicitudDelegacion($solicitud)){
            throw $this->createAccessDeniedException();
        }

        $convenios = $this->getDoctrine()
            ->getRepository(Convenio::class)
            ->getAllBySolicitud($solicitud->getId());

        return $this->render('came/solicitud/show.html.twig', [
            'solicitud' => $this->get('serializer')->normalize(
                $solicitud, 'json', ['attributes' => [
                'id', 'noSolicitud', 'estatusCameFormatted', 'tipoPago', 'fechaComprobanteFormatted',
                'fechaComprobante',
                'institucion' => ['id', 'nombre'],
                'camposClinicosSolicitados', 'camposClinicosAutorizados',
                'campoClinicos' => ['id',
                    'convenio' => ['cicloAcademico' => ['id', 'nombre'],
                        'id', 'vigencia', 'vigenciaFormatted','label', 'carrera' => ['id', 'nombre',
                            'nivelAcademico' => ['id', 'nombre']], 'numero'],
                    'lugaresSolicitados', 'lugaresAutorizados', 'horario', 'unidad' => ['id', 'nombre'],
                    'fechaInicial', 'fechaFinal', 'referenciaBancaria', 'fechaInicialFormatted', 'fechaFinalFormatted',
                    'estatus' => ['id', 'nombre']],
                'pago' => ['id', 'comprobantePago', 'fechaPago', 'fechaPagoFormatted', 'referenciaBancaria', 'factura' => ['fechaFacturacion', 'id', 'fechaFacturacionFormatted']],
                'pagos' => ['id', 'comprobantePago', 'fechaPago', 'fechaPagoFormatted','referenciaBancaria', 'factura' => ['fechaFacturacion', 'id', 'fechaFacturacionFormatted']]]
            ]),
            'convenios' => $this->get('serializer')->normalize($convenios, 'json', ['attributes' => [
                'cicloAcademico' => ['id', 'nombre'],
                'id', 'vigencia','vigenciaFormatted', 'label',
                'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']]]
            ])
        ]);
    }

    /**
     * @Route("/api/solicitud/{id}", methods={"DELETE"}, name="solicitud.delete")
     */
    public function deleteAction($id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }
        if(!$this->validarSolicitudDelegacion($solicitud)){
            throw $this->createAccessDeniedException();
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($solicitud);
        $response = new JsonResponse(['status' => true, "message" => "Solicitud Eliminada con éxito"]);
        return $response;
    }

    /**
     * @Route("/api/solicitud/terminar/{id}", methods={"POST"}, name="solicitud.terminar")
     * @Security("has_role('ROLE_TERMINAR_SOLICITUDES')")
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
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }
        if(!$this->validarSolicitudDelegacion($solicitud)){
            throw $this->createAccessDeniedException();
        }
        $solicitudManager->finalizar($solicitud);
        return $this->jsonResponse(['status' => true]);
    }

    /**
     * @Route("/api/solicitud/validar_montos/{id}", methods={"POST"}, name="solicitud.store_validar_montos")
     * @Security("has_role('ROLE_VALIDACION_MONTOS')")
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
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }
        if(!$this->validarSolicitudDelegacion($solicitud)){
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(ValidaSolicitudType::class, $solicitud);
        $form->get('montos_pagos')->setData($solicitud->getMontosCarreras());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $solicitudManager->validarMontos($form->getData(),
                $form->get('montos_pagos')->getData(), isset($request->request->get('solicitud')['validado']));
            return $this->jsonResponse($result);
        }
        return $this->jsonErrorResponse($form);
    }

    /**
     * @Route("/solicitud/{id}/validar_montos", methods={"GET"}, name="solicitud.validar_montos")
     * @Security("has_role('ROLE_VALIDACION_MONTOS')")
     * @param Request $request
     * @param $id
     */
    public function validarMontosAction(Request $request, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }

        if(!$this->validarSolicitudDelegacion($solicitud)){
            throw $this->createAccessDeniedException();
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
                    'montosCarreras' => ['id', 'montoInscripcion', 'montoColegiatura',
                        'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre'] ]]
                ]]
            )
        ]);
    }

    /**
     * @Route("/solicitud/{solicitud_id}/oficio", methods={"GET"}, name="came.solicitud.oficio_montos")
     * @Security("has_role('ROLE_DESCARGAR_OFICIO_MONTOS')")
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
     * @Route("/solicitud/email/montos_invalidos", methods={"GET"}, name="solicitud.email.montos_invalidos")
     * @param Request $request
     * @param $id
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
        return $this->render('emails/came/montos_invalidos.html.twig', ['solicitud' => $solicitud]);
    }
}
