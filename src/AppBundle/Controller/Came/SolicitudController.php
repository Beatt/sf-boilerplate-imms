<?php

namespace AppBundle\Controller\Came;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\NivelAcademico;
use AppBundle\Entity\Solicitud;
use AppBundle\Form\Type\SolicitudType;
use AppBundle\Service\SolicitudManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
            ->findBy([], [], $perPage, ($page-1) * $perPage);
        return $this->render('came/solicitud/index.html.twig', [
            'solicitudes' => $this->get('serializer')->normalize(
                $solicitudes,
                'json',
                [
                    'attributes' => [
                        'id',
                        'fecha',
                        'estatus',
                        'estatusFormatted',
                        'institucion',
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
        $tokenProvider = $this->container->get('security.csrf.token_manager');
        return $this->render('came/solicitud/create.html.twig', [
            'form' => $form->createView(),
            'token' => $tokenProvider->getToken('solicitud_item')->getValue(),
            'instituciones' => $this->getDoctrine()
                ->getRepository(Institucion::class)->findAll()
        ]);
    }


    /**
     * @Route("/api/solicitud", methods={"POST"}, name="solicitud.store")
     * @param Request $request
     * @param SolicitudManagerInterface $solicitudManager
     */
    public function storeAction(Request $request, SolicitudManagerInterface $solicitudManager)
    {
        $form = $this->createForm(SolicitudType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $result = $solicitudManager->create($form->getData());
            return $this->jsonResponse($result);
        }
        return $this->jsonErrorResponse($form);
    }

    /**
     * @Route("/solicitud/{id}", methods={"GET"}, name="solicitud.edit")
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
        $form = $this->createForm(SolicitudType::class);
        $tokenProvider = $this->container->get('security.csrf.token_manager');
        return $this->render('came/solicitud/edit.html.twig', [
            'form' => $form->createView(),
            'token' => $tokenProvider->getToken('solicitud_item')->getValue()
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
        return $this->render('came/solicitud/show.html.twig', [
            'solicitud' => $this->get('serializer')->normalize(
                $solicitud,
                'json',
                [
                    'attributes' => [
                        'id'
                    ]
                ]
            )
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
}
