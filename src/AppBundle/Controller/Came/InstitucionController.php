<?php


namespace AppBundle\Controller\Came;

use AppBundle\Entity\Institucion;
use AppBundle\Form\Type\InstitucionType;
use AppBundle\Service\InstitucionManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InstitucionController extends \AppBundle\Controller\DIEControllerController
{
    /**
     * @Route("/api/came/institucion/{id}", methods={"POST"}, name="came.institucion.update")
     * @param Request $request
     * @param InstitucionManagerInterface $institucionManager
     * @param $id
     **/
    public function updateAction(Request $request, InstitucionManagerInterface $institucionManager, $id){
        $institucion = $this->getDoctrine()
            ->getRepository(Institucion::class)
            ->find($id);
        if (!$institucion) {
            throw $this->createNotFoundException(
                'Not found for id ' . $id
            );
        }
        $form = $this->createForm(InstitucionType::class, $institucion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $institucionManager->Create($form->getData());
            return $this->jsonResponse(['status' => $result,
                'message' => $result? 'Institución Actualizada con éxito' : 'Se presento un problema al actualizar la institucion']);
        }
        return $this->jsonErrorResponse($form, ['message' => 'Se presento un problema al actualizar la institucion']);
    }
}