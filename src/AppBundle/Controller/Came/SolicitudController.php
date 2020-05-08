<?php

namespace AppBundle\Controller\Came;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Convenio;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\Unidad;
use AppBundle\Form\Type\SolicitudType;
use AppBundle\Service\SolicitudManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SolicitudController extends DIEControllerController
{
    /**
     * @Route("/solicitud", methods={"GET"}, name="solicitud.index")
     */
    public function indexAction(Request $request)
    {
        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $solicitudes = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->findBy([], [], $perPage, ($page - 1) * $perPage);
        return $this->render('came/solicitud/index.html.twig', [
            'solicitudes' => $this->get('serializer')->normalize(
                $solicitudes,
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
            )
        ]);
    }

    /**
     * @Route("/solicitud/create", methods={"GET"}, name="solicitud.create")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(SolicitudType::class);
        $instituciones = $this->getDoctrine()
            ->getRepository(Institucion::class)
            ->findAllPrivate();
        $unidades = $this->getDoctrine()
            ->getRepository(Unidad::class)
            ->getAllUnidadesByDelegacion();
        return $this->render('came/solicitud/create.html.twig', [
            'form' => $form->createView(),
            'instituciones' => $this->get('serializer')->normalize($instituciones, 'json',
                ['attributes' => ['id', 'nombre', 'rfc', 'domicilio', 'telefono', 'correo', 'sitioWeb', 'fax',
                    'convenios' => ['id', 'nombre', 'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']],
                        'cicloAcademico' => ['id', 'nombre'], 'vigencia', 'label']]]),
            'unidades' => $this->get('serializer')->normalize($unidades, 'json',
                ['attributtes' => ['id', 'nombre']])
        ]);
    }


    /**
     * @Route("/api/solicitud", methods={"POST"}, name="solicitud.store")
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
     */
    public function editAction($id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }
        $instituciones = $this->getDoctrine()
            ->getRepository(Institucion::class)
            ->findAllPrivate();
        $unidades = $this->getDoctrine()
            ->getRepository(Unidad::class)
            ->getAllUnidadesByDelegacion();
        $form = $this->createForm(SolicitudType::class);
        return $this->render('came/solicitud/edit.html.twig', [
            'form' => $form->createView(),
            'instituciones' => $this->get('serializer')->normalize($instituciones,
                'json',
                ['attributes' => ['id', 'nombre', 'rfc', 'domicilio', 'telefono', 'correo', 'sitioWeb', 'fax']]),
            'solicitud' => $this->get('serializer')->normalize($solicitud, 'json',
                ['attributes' => ['id', 'campoClinicos' => ['id',
                    'convenio' => ['cicloAcademico' => ['id', 'nombre'],
                        'id', 'vigencia', 'label', 'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']]],
                    'lugaresSolicitados', 'lugaresAutorizados', 'horario', 'unidad' => ['id', 'nombre'],
                    'fechaInicial', 'fechaFinal'], 'institucion' => ['id', 'nombre', 'fax',
                    'telefono', 'correo', 'sitioWeb', 'direccion', 'rfc', 'convenios' => ['id', 'nombre', 'carrera' => ['id', 'nombre', 'nivelAcademico' => ['id', 'nombre']],
                        'cicloAcademico' => ['id', 'nombre'], 'vigencia', 'label']]
                ]]),
            'unidades' => $this->get('serializer')->normalize($unidades, 'json',
                ['attributtes' => ['id', 'nombre']])
        ]);
    }

    /**
     * @Route("/api/solicitud/{id}", methods={"PUT"}, name="solicitud.update")
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

        $convenios = $this->getDoctrine()
            ->getRepository(Convenio::class)
            ->getAllBySolicitud($solicitud->getId());

        return $this->render('came/solicitud/show.html.twig', [
            'solicitud' => $this->get('serializer')->normalize(
                $solicitud, 'json', ['attributes' => [
                'id', 'noSolicitud', 'estatusCameFormatted', 'tipoPago',
                'institucion' => ['id', 'nombre'],
                'camposClinicosSolicitados', 'camposClinicosAutorizados',
                'campoClinicos' => ['id',
                    'convenio' => ['cicloAcademico' => ['id', 'nombre'],
                        'id', 'vigencia', 'label', 'carrera' => ['id', 'nombre',
                            'nivelAcademico' => ['id', 'nombre']], 'numero'],
                    'lugaresSolicitados', 'lugaresAutorizados', 'horario', 'unidad' => ['id', 'nombre'],
                    'fechaInicial', 'fechaFinal'],
                'expediente' => ['id', 'descripcion', 'urlArchivo', 'nombreArchivo', 'fecha'],
                'pago' => ['id', 'comprobantePago', 'fecha', 'factura' => ['fechaFacturacion', 'id', 'zip']]]
            ]),
            'convenios' => $this->get('serializer')->normalize($convenios, 'json', ['attributes' => [
                'cicloAcademico' => ['id', 'nombre'],
                'id', 'vigencia', 'label',
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
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($solicitud);
        $response = new JsonResponse(['status' => true, "message" => "Solicitud Eliminada con éxito"]);
        return $response;
    }

    /**
     * @Route("/api/solicitud/terminar/{id}", methods={"POST"}, name="solicitud.terminar")
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

        $solicitudManager->finalizar($solicitud);
        return $this->jsonResponse(['status' => true]);
    }

    /**
     * @Route("/api/solicitud/validar_montos/{id}", methods={"POST"}, name="solicitud.store_validar_montos")
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

        $result = $solicitudManager->validarMontos($solicitud, $request);

        return $this->jsonResponse($result);
    }

    /**
     * @Route("/solicitud/{id}/validar_montos", methods={"GET"}, name="solicitud.validar_montos")
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

        return $this->render('came/solicitud/valida_montos.html.twig', [
            'solicitud' => $this->get('serializer')->normalize(
                $solicitud, 'json', ['attributes' => [
                    'id', 'noSolicitud',
                    'estatusCameFormatted',
                    'documento', 'urlArchivo',
                    'institucion' => ['id', 'nombre'],
                ]]
            )
        ]);
    }
}
