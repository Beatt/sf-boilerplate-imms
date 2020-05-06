<?php

namespace AppBundle\Controller\Pregrado;

use AppBundle\Entity\Delegacion;
use AppBundle\Controller\DIEControllerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DelegacionController extends DIEControllerController
{
    /**
     * @Route("/api/pregrado/delegacion", methods={"GET"}, name="pregrado.delegacion.list")
     */
    public function listAction(Request $request)
    {
        $delegaciones =  $this->getDoctrine()
            ->getRepository(Delegacion::class)
            ->getAllDelegacionesNotNullRegion();
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize($delegaciones, 'json',
                ['attributes' => [ 'id', 'nombre']
            ])]);
    }
}