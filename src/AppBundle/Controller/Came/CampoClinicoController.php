<?php

namespace AppBundle\Controller\Came;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Solicitud;
use AppBundle\Form\Type\CampoClinicoType;
use AppBundle\Service\CampoClinicoManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CampoClinicoController extends \AppBundle\Controller\DIEControllerController
{
    /**
     * @Route("/api/came/campo_clinico", methods={"POST"}, name="came.campo_clinico.store")
     * @param CampoClinicoManagerInterface $campo_clinico_manager
     * @param Request $request
     **/
    public function storeAction(Request $request, CampoClinicoManagerInterface $campo_clinico_manager)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($request->request->get('campo_clinico')['solicitud']);
        $form = $this->createForm(CampoClinicoType::class);
        $form->handleRequest($request);
        if($solicitud && $form->isSubmitted() && $form->isValid()) {
            $result = $campo_clinico_manager->create($form->getData());
            return $this->jsonResponse($result);
        }
        return $this->jsonErrorResponse($form);
    }

    /**
     * @Route("/came/campo_clinico/create", methods={"GET"}, name="came.campo_clinico.create")
     */
    public function createAction(){
        $form = $this->createForm(CampoClinicoType::class);
        return $this->render('came/campo_clinico/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/api/came/solicitud/{id}/campos_clinicos", methods={"GET"}, name="solicitud.index.campo_clinico.json")
     * @param $id_solicitud
     */
    public function indexApiAction(Request $request, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }

        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $camposClinicos = $this->getDoctrine()
            ->getRepository(CampoClinico::class)
            ->getAllCamposClinicosBySolicitud($id, $perPage, $page, $request->query->all());
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize(
                $camposClinicos,
                'json',
                [
                    'attributes' => ['id',
                        'convenio' => ['cicloAcademico' => ['id', 'nombre'],
                            'id', 'vigencia', 'label', 'carrera' => ['id', 'nombre',
                                'nivelAcademico' => ['id', 'nombre']], 'numero'],
                        'lugaresSolicitados', 'lugaresAutorizados', 'horario', 'unidad' => ['id', 'nombre'],
                        'fechaInicial', 'fechaFinal', 'referenciaBancaria']
                ]
            )
        ]);
    }
}