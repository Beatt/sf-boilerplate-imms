<?php

namespace AppBundle\Controller\Came;

use AppBundle\Entity\CampoClinico;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CampoClinicoController extends Controller
{
    /**
     * @Route("/api/campo-clinico", methods={"GET"}, name="campo-clinico.index")
     */
    public function index()
    {
        $campos_clinicos = $this->getDoctrine()
            ->getRepository(CampoClinico::class)
            ->findAll();
        $response = new JsonResponse(['data' => $campos_clinicos]);
        return $response;
    }

    /**
     * @Route("/api/campo-clinico", methods={"POST"}, name="campo-clinico.store")
     */
    public function store(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $campo_clinico = new CampoClinico();
        $campo_clinico->setCarreraId($request->request->get('carrera_id'));
        $entityManager->persist($campo_clinico);
        $entityManager->flush();
        $response = new JsonResponse(['status' => true, "message" => "Campo Clinico Creada con éxito", "data" =>$campo_clinico]);
        return $response;
    }

    /**
     * @Route("/api/campo-clinico/{id}", methods={"PUT"}, name="campo-clinico.update")
     */
    public function update(Request $request, $id)
    {
        $campo_clinico = $this->getDoctrine()
            ->getRepository(CampoClinico::class)
            ->find($id);

        if (!$campo_clinico) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $campo_clinico->setSolicitudId($request->request->get('solicitud_id'));
        $entityManager->persist($campo_clinico);

        $response = new JsonResponse(['status' => true, "message" => "Solicitud Actualizada con éxito"]);
        return $response;
    }

    /**
     * @Route("/api/campo-clinico/{id}", methods={"GET"}, name="campo-clinico.show")
     */
    public function show($id)
    {
        $campo_clinico = $this->getDoctrine()
            ->getRepository(CampoClinico::class)
            ->find($id);

        if (!$campo_clinico) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }
        $response = new JsonResponse(['data' => $campo_clinico]);
        return $response;
    }

    /**
     * @Route("/api/campo-clinico/{id}", methods={"DELETE"}, name="campo-clinico.delete")
     */
    public function delete($id)
    {
        $campo_clinico = $this->getDoctrine()
            ->getRepository(CampoClinico::class)
            ->find($id);

        if (!$campo_clinico) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($campo_clinico);
        $response = new JsonResponse(['status' => true, "message" => "Campo clinico Eliminado con éxito"]);
        return $response;
    }
}
