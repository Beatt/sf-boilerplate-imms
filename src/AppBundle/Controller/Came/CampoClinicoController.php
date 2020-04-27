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
}