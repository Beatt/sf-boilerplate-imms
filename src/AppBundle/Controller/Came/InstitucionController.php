<?php


namespace AppBundle\Controller\Came;

use AppBundle\Entity\Institucion;
use AppBundle\Entity\Usuario;
use AppBundle\Form\Type\InstitucionType;
use AppBundle\Service\InstitucionManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class InstitucionController extends \AppBundle\Controller\DIEControllerController
{
    /**
     * @Route("/came/api/institucion/{id}", methods={"POST"}, name="came.institucion.update")
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
        $correo_anterior = $institucion->getCorreo();
        $form = $this->createForm(InstitucionType::class, $institucion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $institucionManager->Create($form->getData());
            $institucion = $this->getDoctrine()
                ->getRepository(Institucion::class)
                ->find($id);
            if($institucion->getCorreo() !== $correo_anterior){
                try{
                    $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['correo' => $correo_anterior]);
                    if($usuario){
                        $usuario->setActivo(false);
                        $this->getDoctrine()->getManager()->persist($usuario);
                    }
                    $institucion->setUsuario(null);
                    $this->getDoctrine()->getManager()->persist($institucion);
                    $this->getDoctrine()->getManager()->flush();
                }catch (\Exception $ex){
                    return $this->failedResponse($ex->getMessage());
                }
            }
            return $this->jsonResponse($result);
        }
        return $this->jsonErrorResponse($form, ['message' => 'Se presentó un problema al actualizar la institución']);
    }
}