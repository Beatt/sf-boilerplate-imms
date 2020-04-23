<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Entity\Institucion;
use AppBundle\Form\Type\InstitucionType;
use AppBundle\Service\InstitucionManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstitucionController extends Controller
{
    /**
     * @Route("/instituciones/{id}", name="instituciones#index")
     * @param integer $id
     * @return Response
     */
    public function indexAction($id)
    {
        $institucion = $this->get('doctrine')->getRepository(Institucion::class)
            ->find($id);

        return $this->render('institucion_educativa/institucion/index.html.twig', [
            'institucion' => $this->get('serializer')->normalize(
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
        ]);
    }

    /**
     * @Route("/instituciones/{id}/editar", name="instituciones#update", methods={"POST", "GET"})
     * @param integer $id
     * @param Request $request
     * @param InstitucionManagerInterface $institucionManager
     * @return Response
     */
    public function updateAction($id, Request $request, InstitucionManagerInterface $institucionManager)
    {
        $institucion = $this->get('doctrine')->getRepository(Institucion::class)
            ->find($id);

        $form = $this->createForm(InstitucionType::class, $institucion, [
            'action' => $this->generateUrl('instituciones#update', [
                'id' => $id
            ]),
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $result = $institucionManager->Create($form->getData());

            return new JsonResponse([
                'message' => $result ?
                    "¡La información se actualizado correctamente!" :
                    '¡Ha ocurrido un problema, intenta más tarde!',
                'status' => $result ?
                    Response::HTTP_OK :
                    Response::HTTP_UNPROCESSABLE_ENTITY
            ]);
        }

        return $this->render('institucion_educativa/institucion/update.html.twig', [
            'form' => $form->createView(),
            'institucion' => $this->get('serializer')->normalize(
                $institucion,
                'json',
                [
                    'attributes' => [
                        'id',
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
        ]);
    }
}
