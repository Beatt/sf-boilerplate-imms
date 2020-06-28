<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Institucion;
use AppBundle\Exception\NotFoundInstitucionException;
use AppBundle\Form\Type\InstitucionType;
use AppBundle\Normalizer\InstitucionPerfilNormalizerInterface;
use AppBundle\Repository\ConvenioRepositoryInterface;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Service\InstitucionManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @param TranslatorInterface $translator
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
        if(!$institucion) {
            throw $this->createNotFoundInstitucionException();
        }

        $form = $this->createForm(InstitucionType::class, $institucion, [
            'action' => $this->generateUrl('ie#perfil', [
                'id' => $institucion->getId()
            ]),
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $institucionManager->create($form->getData());

            $this->addFlash(
                'success',
                'Se han actualizado correctamente los datos de contacto de la institución'
            );

            return $institucion->isConfirmacionInformacion() ?
                $this->redirectToRoute('ie#inicio') :
                $this->redirectToRoute('ie#perfil');
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
