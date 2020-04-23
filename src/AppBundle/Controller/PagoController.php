<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Pago;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;;
use Symfony\Component\HttpFoundation\JsonResponse;

class PagoController extends Controller
{
    /**
     * @Route("/api/pago", methods={"GET"}, name="pago.index")
     */
    public function index()
    {
        $pagos = $this->getDoctrine()
            ->getRepository(Pago::class)
            ->findAll();
        $response = new JsonResponse(['data' => $pagos]);
        return $response;
    }

    /**
     * @Route("/api/pago", methods={"POST"}, name="pago.store")
     */
    public function store(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $pago = new Pago();
        $pago->setSolicitudId($request->request->get('solicitud_id'));
        $entityManager->persist($pago);
        $entityManager->flush();
        $response = new JsonResponse(['status' => true, "message" => "Pago creado con éxito", "data" => $pago]);
        return $response;
    }

    /**
     * @Route("/api/pago/{id}", methods={"PUT"}, name="pago.update")
     */
    public function update(Request $request, $id)
    {
        $pago = $this->getDoctrine()
            ->getRepository(Pago::class)
            ->find($id);

        if (!$pago) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $pago->setSolicitudId($request->request->get('solicitud_id'));
        $entityManager->persist($pago);

        $response = new JsonResponse(['status' => true, "message" => "Pago Actualizado con éxito"]);
        return $response;
    }

    /**
     * @Route("/api/pago/{id}", methods={"GET"}, name="pago.show")
     */
    public function show($id)
    {
        $pago = $this->getDoctrine()
            ->getRepository(Pago::class)
            ->find($id);

        if (!$pago) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }
        $response = new JsonResponse(['data' => $pago]);
        return $response;
    }

    /**
     * @Route("/api/pago/{id}", methods={"DELETE"}, name="pago.delete")
     */
    public function delete($id)
    {
        $pago = $this->getDoctrine()
            ->getRepository(Pago::class)
            ->find($id);

        if (!$pago) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($pago);
        $response = new JsonResponse(['status' => true, "message" => "Pago Eliminado con éxito"]);
        return $response;
    }
}
