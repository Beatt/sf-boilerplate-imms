<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CampoClinico;
use AppBundle\Repository\CampoClinicoRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConveniosController extends Controller
{

    /**
     * @Route("/instituciones/{id}/convenios", name="convenios#index")
     * @param CampoClinicoRepositoryInterface $campoClinicoRepository
     * @return Response
     */
    public function indexAction(CampoClinicoRepositoryInterface $campoClinicoRepository)
    {
        $lastInstitucion = $this->get('doctrine')->getRepository('AppBundle:Institucion')
            ->findAll();

        $camposClinicos = $campoClinicoRepository->getAllCamposClinicosByInstitucion(
            $lastInstitucion[0]->getId()
        );

        return new JsonResponse($this->get('serializer')->normalize(
            $camposClinicos,
            'json',
            [
                'attributes' => [
                    'cicloAcademico' => [
                        'nombre'
                    ],
                    'convenio' => [
                        'id',
                        'vigencia',
                        'carrera' => [
                            'nombre',
                            'nivelAcademico' => [
                                'nombre'
                            ]
                        ]
                    ]
                ]
            ]
        ));
    }
}