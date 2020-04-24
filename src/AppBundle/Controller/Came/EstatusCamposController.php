<?php

namespace AppBundle\Controller\Came;

use AppBundle\Entity\EstatusCampo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EstatusCamposController extends Controller
{
    /**
     * @Route("/api/estatus-campo", methods={"GET"}, name="estatus-campo.index")
     */
    public function index()
    {
        $estatus = $this->getDoctrine()
            ->getRepository(EstatusCampo::class)
            ->findAll();
        $response = new JsonResponse(['data' => $estatus]);
        return $response;
    }

    /**
     * @Route("/api/estatus-campo", methods={"POST"}, name="estatus-campo.store")
     */
    public function store(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $estado = new EstatusCampo();
        $estado->setEstatus($request->request->get('estatus'));
        $entityManager->persist($estado);
        $entityManager->flush();
        $response = new JsonResponse(['status' => true, "message" => "Estado creado con éxito", "data" => $estado]);
        return $response;
    }

    /**
     * @Route("/api/estatus-campo/{id}", methods={"PUT"}, name="estatus-campo.update")
     */
    public function update(Request $request, $id)
    {
        $estado = $this->getDoctrine()
            ->getRepository(EstatusCampo::class)
            ->find($id);

        if (!$estado) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $estado->setEstatus($request->request->get('estatus'));
        $entityManager->persist($estado);

        $response = new JsonResponse(['status' => true, "message" => "Estado Actualizado con éxito"]);
        return $response;
    }

    /**
     * @Route("/api/estatus-campo/{id}", methods={"GET"}, name="estatus-campo.show")
     */
    public function show($id)
    {
        $estado = $this->getDoctrine()
            ->getRepository(EstatusCampo::class)
            ->find($id);

        if (!$estado) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }
        $response = new JsonResponse(['data' => $estado]);
        return $response;
    }

    /**
     * @Route("/api/estatus-campo/{id}", methods={"DELETE"}, name="estatus-campo.delete")
     */
    public function delete($id)
    {
        $estado = $this->getDoctrine()
            ->getRepository(EstatusCampo::class)
            ->find($id);

        if (!$estado) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($estado);
        $response = new JsonResponse(['status' => true, "message" => "Estado Eliminado con éxito"]);
        return $response;
    }
}
