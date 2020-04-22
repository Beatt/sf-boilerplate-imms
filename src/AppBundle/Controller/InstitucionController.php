<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Institucion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class InstitucionController extends Controller
{
    /**
     * @Route("/instituciones/{id}", name="instituciones#index")
     * @param integer $id
     * @return JsonResponse
     */
    public function indexAction($id)
    {
        $institucion = $this->get('doctrine')->getRepository(Institucion::class)
            ->find($id);

        return new JsonResponse(
            $this->get('serializer')->normalize(
                $institucion,
                'json',
                [
                    'attributes' => [
                        'nombre',
                        'rfc',
                        'direccion',
                        'correo',
                        'telefono',
                        'fax',
                        'sitioWeb',
                        'cedulaIdentificacion'
                    ]
                ]
            )
        );
    }
}
