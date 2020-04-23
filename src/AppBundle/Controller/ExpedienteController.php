<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Expediente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExpedienteController extends Controller
{
    /**
     * @Route("/api/expediente", methods={"GET"}, name="expediente.index")
     */
    public function index()
    {
        $expedientes = $this->getDoctrine()
            ->getRepository(Expediente::class)
            ->findAll();
        $response = new JsonResponse(['data' => $expedientes]);
        return $response;
    }

    /**
     * @Route("/api/expediente", methods={"POST"}, name="expediente.store")
     */
    public function store(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $expediente = new Expediente();
        $expediente->setSolicitudId($request->request->get('solicitud_id'));
        $entityManager->persist($expediente);
        $entityManager->flush();
        $response = new JsonResponse(['status' => true, "message" => "Expediente creado con éxito", "data" => $expediente]);
        return $response;
    }

    /**
     * @Route("/api/expediente/{id}", methods={"PUT"}, name="expediente.update")
     */
    public function update(Request $request, $id)
    {
        $expediente = $this->getDoctrine()
            ->getRepository(Expediente::class)
            ->find($id);

        if (!$expediente) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $expediente->setSolicitudId($request->request->get('solicitud_id'));
        $entityManager->persist($expediente);

        $response = new JsonResponse(['status' => true, "message" => "Expediente Actualizado con éxito"]);
        return $response;
    }

    /**
     * @Route("/api/expediente/{id}", methods={"GET"}, name="expediente.show")
     */
    public function show($id)
    {
        $expediente = $this->getDoctrine()
            ->getRepository(Expediente::class)
            ->find($id);

        if (!$expediente) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }
        $response = new JsonResponse(['data' => $expediente]);
        return $response;
    }

    /**
     * @Route("/api/expediente/{id}", methods={"DELETE"}, name="expediente.delete")
     */
    public function delete($id)
    {
        $expediente = $this->getDoctrine()
            ->getRepository(Expediente::class)
            ->find($id);

        if (!$expediente) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($expediente);
        $response = new JsonResponse(['status' => true, "message" => "Solicitud Eliminada con éxito"]);
        return $response;
    }
}
