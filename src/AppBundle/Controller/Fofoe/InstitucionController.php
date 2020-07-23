<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Usuario;
use AppBundle\Form\Type\InstitucionType;
use AppBundle\Normalizer\DetalleInstitucionNormalizerInterface;
use AppBundle\Normalizer\InstitucionPerfilNormalizerInterface;
use AppBundle\Repository\ConvenioRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Service\InstitucionManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/fofoe")
 * 
 */
class InstitucionController extends DIEControllerController
{
    /**
     * @Route("/detalle-ie/{id}", name="ie#detalle-ie", methods={"GET"})
     * @param int $id
     * @param Request $request
     * @param InstitucionManagerInterface $institucionManager
     * @param ConvenioRepositoryInterface $convenioRepository
     * @param InstitucionPerfilNormalizerInterface $institucionPerfilNormalizer
     * @param InstitucionRepositoryInterface $institucionRepository
     * @param PagoRepositoryInterface $pagoRepository
     * @return Response
     */
    public function detalleIEAction(
        $id,
        Request $request,
        InstitucionManagerInterface $institucionManager,
        ConvenioRepositoryInterface $convenioRepository,
        InstitucionPerfilNormalizerInterface $institucionPerfilNormalizer,
        InstitucionRepositoryInterface $institucionRepository,
        PagoRepositoryInterface $pagoRepository
    ) {
        /** @var Institucion $institucion */

        /*$institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();*/
        $institucion = $institucionRepository->find($id);

        $form = $this->createForm(InstitucionType::class, $institucion, [
            'action' => $this->generateUrl('ie#detalle-ie', [
                'id' => $institucion->getId()
            ]),
        ]);

        $pagos = $pagoRepository->getAllPagosByInstitucion($institucion->getId());

        $convenios = $convenioRepository->getConveniosUnicosByInstitucionId(
            $institucion->getId()
        );

        return $this->render('fofoe/detalle_ie/index.html.twig', [
            'convenios' => $institucionPerfilNormalizer->normalizeConvenios($convenios),
            'institucion' => $institucionPerfilNormalizer->normalizeInstitucion($institucion),
            'errores' => $this->getFormErrors($form),
            'pagos' => $this->getNormalizePagos($pagos)
        ]);
    }

    public function menuAction()
    {
        /** @var Usuario $user */
        $user = $this->getUser();

        return $this->render('ie/institucion/_menu.twig', [
            'institucion' => $user->getInstitucion()
        ]);
    }

    private function getNormalizePagos($pagos)
    {
        return $this->get('serializer')->normalize(
            $pagos,
            'json',
            [
                'attributes' => [
                    'id',
                    'fechaPago',
                    'monto',
                    'requiereFactura',
                    'validado',
                    'factura' => [
                        'zip',
                        'monto'
                    ],
                    'solicitud' => [
                        'noSolicitud',
                        'fecha'
                    ]
                ]
            ]
        );
    }
}
