<?php

namespace AppBundle\Controller\Pregrado;

use AppBundle\Entity\CicloAcademico;
use AppBundle\Controller\DIEControllerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CicloAcademicoController extends DIEControllerController
{
    /**
     * @Route("/api/pregrado/ciclo_academico", methods={"GET"}, name="pregrado.ciclo_academico.list")
     */
    public function listAction(Request $request)
    {
        $delegaciones =  $this->getDoctrine()
            ->getRepository(CicloAcademico::class)
            ->findByActivo(true);
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize($delegaciones, 'json',
                ['attributes' => [ 'id', 'nombre']
                ])]);
    }
}