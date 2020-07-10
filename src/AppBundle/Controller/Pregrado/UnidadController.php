<?php

namespace AppBundle\Controller\Pregrado;

use AppBundle\Entity\Unidad;
use AppBundle\Controller\DIEControllerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UnidadController extends DIEControllerController
{
  /**
   * @Route("/api/pregrado/unidad/{delegacion_id}", methods={"GET"}, name="pregrado.unidad.list")
   */
  public function listAction(Request $request, $delegacion_id)
  {
    $delegaciones =  $this->getDoctrine()
      ->getRepository(Unidad::class)
      ->findBy(['delegacion'=> $delegacion_id]);
    return $this->jsonResponse([
      'object' => $this->get('serializer')->normalize($delegaciones, 'json',
        ['attributes' => [ 'id', 'nombre']
        ])]);
  }
}