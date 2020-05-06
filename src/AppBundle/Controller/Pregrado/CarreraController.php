<?php

namespace AppBundle\Controller\Pregrado;

use AppBundle\Entity\Carrera;
use AppBundle\Controller\DIEControllerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarreraController extends DIEControllerController
{
    /**
     * @Route("/api/pregrado/carrera", methods={"GET"}, name="pregrado.carrera.list")
     */
    public function listAction(Request $request)
    {
        $carreras =  $this->getDoctrine()
            ->getRepository(Carrera::class)
            ->getAllCarrerasActivas();
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize($carreras, 'json',
                ['attributes' => [ 'id', 'nombre', 'nivelAcademico' => ['nombre']]
            ])]);
    }
}