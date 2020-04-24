<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Entity\Institucion;
use AppBundle\Entity\CampoClinico;
use AppBundle\Form\Type\InstitucionType;
use AppBundle\Service\InstitucionManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstitucionController extends Controller
{
    /**
     * @Route("/instituciones/{id}/editar", name="instituciones#update", methods={"POST", "GET"})
     * @param integer $id
     * @param Request $request
     * @param InstitucionManagerInterface $institucionManager
     * @return Response
     */
    public function updateAction($id, Request $request, InstitucionManagerInterface $institucionManager)
    {
        $institucion = $this->get('doctrine')->getRepository(Institucion::class)
            ->find($id);

        $form = $this->createForm(InstitucionType::class, $institucion, [
            'action' => $this->generateUrl('instituciones#update', [
                'id' => $id
            ]),
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $result = $institucionManager->Create($form->getData());

            return new JsonResponse([
                'message' => $result ?
                    "¡La información se actualizado correctamente!" :
                    '¡Ha ocurrido un problema, intenta más tarde!',
                'status' => $result ?
                    Response::HTTP_OK :
                    Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        }

        $campoClinicoRepository = $this->get('doctrine')->getRepository(CampoClinico::class);

        $camposClinicos = $campoClinicoRepository->getAllCamposClinicosByInstitucion(
            $institucion->getId()
        );

        return $this->render('institucion_educativa/institucion/update.html.twig', [
            'form' => $form->createView(),
            'convenios' => $this->get('serializer')->normalize(
                $camposClinicos,
            'json',
            [
                'attributes' => [
                    'id',
                    'cicloAcademico' => [
                        'nombre'
                    ],
                    'convenio' => [
                        'id',
                        'vigencia',
                        'label',
                        'carrera' => [
                            'nombre',
                            'nivelAcademico' => [
                                'nombre'
                            ]
                        ]
                    ]
                ]
            ]
            ),
            'institucion' => $this->get('serializer')->normalize(
                $institucion,
                'json',
                [
                    'attributes' => [
                        'id',
                        'nombre',
                        'rfc',
                        'direccion',
                        'correo',
                        'telefono',
                        'fax',
                        'sitioWeb',
                        'cedulaIdentificacion'
                    ]
                ]
            )
        ]);
    }

    /**
     * @Route("/instituciones/{id}/solicitudes/{solicitudId}", name="instituciones#detail")
     * @param integer $id
     * @return Response
     */
    public function detailAction($id, $solicitudId)
    {
        $campoClinicoRepository = $this->get('doctrine')->getRepository(CampoClinico::class);

        $camposClinicos = $campoClinicoRepository->getAllCamposClinicosByRequest(
            $solicitudId
        );

        $institucion = $this->get('doctrine')->getRepository(Institucion::class)
            ->find($id);

        return $this->render('institucion_educativa/institucion/detail.html.twig',[
            'institucion' => $institucion,
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
            )
        ]);
    }

    public function menuAction($id)
    {
        $institucion = $this->get('doctrine')->getRepository(Institucion::class)
            ->find($id);

        return $this->render('institucion_educativa/institucion/_menu.twig', [
            'institucion' => $institucion
        ]);
    }
}
