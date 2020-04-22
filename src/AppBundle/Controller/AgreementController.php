<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CampoClinico;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgreementController extends Controller
{

    /**
     * @Route("/convenios", name="convenios")
     * @return Response
     */
    public function index()
    {
        $lastInstitucion = $this->get('doctrine')->getRepository('AppBundle:Institucion')
            ->findAll();

        $camposClinicos = $this->get('doctrine')->getRepository(CampoClinico::class)
            ->getAllCamposClinicosByInstitucion($lastInstitucion[0]->getId());

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
