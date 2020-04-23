<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Solicitud;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SolicitudController extends Controller
{
    /**
     * @Route("/api/solicitud", methods={"GET"}, name="solicitud.index")
     */
    public function index(Request $request)
    {
        $solicitudes = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->findAll();
        $response = new JsonResponse(['data' => $solicitudes]);
        return $response;
    }

    /**
     * @Route("/api/solicitud", methods={"POST"}, name="solicitud.store")
     */
    public function store(Request $request)
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
     * @Route("/api/solicitud/{id}", methods={"PUT"}, name="solicitud.update")
     */
    public function update(Request $request, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $solicitud->setEstatus($request->request->get('estatus'));
        $entityManager->persist($solicitud);

        $response = new JsonResponse(['status' => true, "message" => "Solicitud Actualizada con éxito"]);
        return $response;
    }

    /**
     * @Route("/api/solicitud/{id}", methods={"GET"}, name="solicitud.show")
     */
    public function show($id)
    {

        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }
        $response = new JsonResponse(['data' => $solicitud]);
        return $response;
    }

    /**
     * @Route("/api/solicitud/{id}", methods={"DELETE"}, name="solicitud.delete")
     */
    public function delete($id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($solicitud);
        $response = new JsonResponse(['status' => true, "message" => "Solicitud Eliminada con éxito"]);
        return $response;
    }
}
