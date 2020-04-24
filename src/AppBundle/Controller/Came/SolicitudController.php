<?php

namespace AppBundle\Controller\Came;

use AppBundle\Entity\Solicitud;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SolicitudController extends Controller
{
    /**
     * @Route("/solicitud", methods={"GET"}, name="solicitud.index")
     */
    public function indexAction(Request $request)
    {
        $solicitudes = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->findAll();
        return $this->render('came/solicitud/index.html.twig', [
            'solicitudes' => $this->get('serializer')->normalize(
                $solicitudes,
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
     * @Route("/solicitud/create", methods={"GET"}, name="solicitud.create")
     */
    public function createAction()
    {
        return $this->render('came/solicitud/create.html.twig');
    }


    /**
     * @Route("/api/solicitud", methods={"POST"}, name="solicitud.store")
     */
    public function storeAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $solicitud = new Solicitud();
        $solicitud->setEstatus($request->request->get('estatus'));
        $entityManager->persist($solicitud);
        $entityManager->flush();
        $response = new JsonResponse(['status' => true, "message" => "Solicitud creada con éxito", "data" => $solicitud]);
        return $response;
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
        return $this->render('came/solicitud/edit.html.twig', [
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
     * @Route("/api/solicitud/{id}", methods={"PUT"}, name="solicitud.update")
     */
    public function updateAction(Request $request, $id)
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
        $solicitud->setEstatus($request->request->get('estatus'));
        $entityManager->persist($solicitud);

        $response = new JsonResponse(['status' => true, "message" => "Solicitud Actualizada con éxito"]);
        return $response;
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
