<?php

namespace AppBundle\Controller\Came;

use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\Solicitud;
use AppBundle\Form\Type\CampoClinicoType;
use AppBundle\Service\CampoClinicoManagerInterface;
use AppBundle\Service\GeneradorCredencialesInterface;
use AppBundle\Service\GeneradorFormatoFofoeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CampoClinicoController extends \AppBundle\Controller\DIEControllerController
{
    /**
     * @Route("/came/api/campo_clinico", methods={"POST"}, name="came.campo_clinico.store")
     * @param CampoClinicoManagerInterface $campo_clinico_manager
     * @param Request $request
     **/
    public function storeAction(Request $request, CampoClinicoManagerInterface $campo_clinico_manager)
    {
        $solicitud_id = $request->request->get('campo_clinico')['solicitud'];
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($solicitud_id);
        if (!$solicitud) {
            return $this->httpErrorResponse('Not Found', Response::HTTP_NOT_FOUND);
        }
        if(!$this->validarSolicitudDelegacion($solicitud)){
            return $this->httpErrorResponse();
        }
        if(!in_array($solicitud->getEstatus(), [Solicitud::CREADA])){
            return $this->httpErrorResponse('No puedes modificar la solicitud '.$solicitud->getNoSolicitud());
        }
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
     * @Route("/came/api/solicitud/{id}/campos_clinicos", methods={"GET"}, name="solicitud.index.campo_clinico.json")
     * @param $id_solicitud
     */
    public function indexApiAction(Request $request, $id)
    {
        $solicitud = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->find($id);

        if (!$solicitud) {
            return $this->httpErrorResponse('Not Found', Response::HTTP_NOT_FOUND);
        }
        if(!$this->validarSolicitudDelegacion($solicitud)){
            return $this->httpErrorResponse('No puedes ver una solicitud de otra delegación');
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
                        'fechaInicial', 'fechaFinal', 'referenciaBancaria', 'fechaInicialFormatted',
                        'fechaFinalFormatted', 'estatus' => ['id', 'nombre']]
                ]
            )
        ]);
    }

    /**
     * @Route("/formato/campo_clinico/{campo_clinico_id}/formato_fofoe/show", methods={"GET"}, name="campo_clinico.formato_fofoe.show")
     * @param $campo_clinico_id
     */
    public function showFormatoFofoeAction($campo_clinico_id)
    {
        $campo_clinico = $this->getDoctrine()
            ->getRepository(CampoClinico::class)
            ->find($campo_clinico_id);

        if (!$campo_clinico) {
            throw $this->createNotFoundException(
                'Not found for id ' . $campo_clinico
            );
        }
        return  $this->render('formatos/fofoe.html.twig', ['campo_clinico' => $campo_clinico, 'came' => null]);
    }

    /**
     * @Route("/formato/campo_clinico/{campo_clinico_id}/formato_fofoe/download", methods={"GET"}, name="campo_clinico.formato_fofoe.download")
     * @param Request $request
     * @param GeneradorFormatoFofoeInterface $generadorFormatoFofoe
     * @param $campo_clinico_id
     */
    public function downloadFormatoFofoeAction(Request $request, GeneradorFormatoFofoeInterface $generadorFormatoFofoe, $campo_clinico_id)
    {
        $campo_clinico = $this->getDoctrine()
            ->getRepository(CampoClinico::class)
            ->find($campo_clinico_id);

        if (!$campo_clinico) {
            throw $this->createNotFoundException(
                'Not found for id ' . $campo_clinico
            );
        }

        $overwrite = $request->query->get('overwrite', false);
        return $generadorFormatoFofoe->responsePdf($this->container->getParameter('formato_fofoe_dir'), $campo_clinico, null, $overwrite);

    }

    /**
     * @Route("/formato/campo_clinico/{campo_clinico_id}/credenciales/show", methods={"GET"}, name="campo_clinico.credenciales.show")
     * @param $campo_clinico_id
     */
    public function showCredencialesAction($campo_clinico_id)
    {
        $campo_clinico = $this->getDoctrine()
            ->getRepository(CampoClinico::class)
            ->find($campo_clinico_id);

        if (!$campo_clinico) {
            throw $this->createNotFoundException(
                'Not found for id ' . $campo_clinico
            );
        }
        if(!$this->validarSolicitudDelegacion($campo_clinico->getSolicitud())){
            throw $this->createAccessDeniedException();
        }
        return  $this->render('formatos/credenciales.html.twig', ['campo_clinico' => $campo_clinico, 'total' => $campo_clinico->getLugaresAutorizados()]);
    }

    /**
     * @Route("/formato/campo_clinico/{campo_clinico_id}/credenciales/download", methods={"GET"}, name="campo_clinico.credenciales.download")
     * @param Request $request
     * @param GeneradorCredencialesInterface $generadorCredenciales
     * @param $campo_clinico_id
     */
    public function downloadCredencialesAction(Request $request, GeneradorCredencialesInterface $generadorCredenciales, $campo_clinico_id)
    {
        $campo_clinico = $this->getDoctrine()
            ->getRepository(CampoClinico::class)
            ->find($campo_clinico_id);

        if (!$campo_clinico) {
            throw $this->createNotFoundException(
                'Not found for id ' . $campo_clinico
            );
        }
        if(!$this->validarSolicitudDelegacion($campo_clinico->getSolicitud())){
            throw $this->createAccessDeniedException();
        }
        $overwrite = $request->query->get('overwrite', false);
        return $generadorCredenciales->responsePdf($this->container->getParameter('credenciales_dir'), $campo_clinico, $overwrite);

    }
}