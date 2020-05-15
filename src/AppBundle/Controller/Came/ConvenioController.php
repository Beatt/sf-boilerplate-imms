<?php


namespace AppBundle\Controller\Came;


use AppBundle\Entity\Convenio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConvenioController extends \AppBundle\Controller\DIEControllerController
{
    /**
     * @Route("/api/came/convenio/{institucion_id}", methods={"GET"}, name="came.convenio.show")
     */
    public function showAction(Request $request, $institucion_id)
    {
        $convenios =  $this->getDoctrine()
            ->getRepository(Convenio::class)
            ->getAllNivelesByConvenio($institucion_id);
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize($convenios, 'json',
                ['attributes' => [
                    'cicloAcademico' => [
                        'id',
                        'nombre'
                    ],
                    'id',
                    'vigencia',
                    'label',
                    'carrera' => [
                        'id',
                        'nombre', 'nivelAcademico' => ['id', 'nombre']
                    ]
                ]])
        ]);
    }
}