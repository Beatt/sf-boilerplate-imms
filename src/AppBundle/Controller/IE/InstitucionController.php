<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Institucion;
use AppBundle\Form\Type\InstitucionType;
use AppBundle\Normalizer\InstitucionPerfilNormalizerInterface;
use AppBundle\Repository\ConvenioRepositoryInterface;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Service\InstitucionManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ie")
 */
class InstitucionController extends DIEControllerController
{
    /**
     * @Route("/perfil", name="ie#perfil", methods={"POST", "GET"})
     * @param Request $request
     * @param InstitucionManagerInterface $institucionManager
     * @param ConvenioRepositoryInterface $convenioRepository
     * @param InstitucionPerfilNormalizerInterface $institucionPerfilNormalizer
     * @return Response
     */
    public function perfilAction(
        Request $request,
        InstitucionManagerInterface $institucionManager,
        ConvenioRepositoryInterface $convenioRepository,
        InstitucionPerfilNormalizerInterface $institucionPerfilNormalizer
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();

        $form = $this->createForm(InstitucionType::class, $institucion, [
            'action' => $this->generateUrl('ie#perfil', [
                'id' => $institucion->getId()
            ]),
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $result = $institucionManager->Create($form->getData());

            $this->addFlash('success', 'Se ha guardado correctamente los datos de la instituciòn');

            /*return new JsonResponse([
                'message' => $result ?
                    "¡La información se actualizado correctamente!" :
                    '¡Ha ocurrido un problema, intenta más tarde!',
                'status' => $result['status'] ?
                    Response::HTTP_OK :
                    Response::HTTP_UNPROCESSABLE_ENTITY
            ]);*/
        }


        $convenios = $convenioRepository->getConveniosUnicosByInstitucionId(
            $institucion->getId()
        );

        return $this->render('ie/institucion/perfil.html.twig', [
            'convenios' => $institucionPerfilNormalizer->normalizeConvenios($convenios),
            'institucion' => $institucionPerfilNormalizer->normalizeInstitucion($institucion),
            'errores' => $this->getFormErrors($form)
        ]);
    }

    public function menuAction($id, InstitucionRepositoryInterface $institucionRepository)
    {
        $institucion = $institucionRepository->find($id);

        return $this->render('ie/institucion/_menu.twig', [
            'institucion' => $institucion
        ]);
    }
}
