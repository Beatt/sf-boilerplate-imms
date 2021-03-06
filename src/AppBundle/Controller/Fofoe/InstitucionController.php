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
use AppBundle\Repository\SolicitudRepositoryInterface;
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
     * @param SolicitudRepositoryInterfac $solicitudRepository
     * @return Response
     */
    public function detalleIEAction(
        $id,
        Request $request,
        InstitucionManagerInterface $institucionManager,
        ConvenioRepositoryInterface $convenioRepository,
        InstitucionPerfilNormalizerInterface $institucionPerfilNormalizer,
        InstitucionRepositoryInterface $institucionRepository,
        SolicitudRepositoryInterface $solicitudRepository
    ) {
        /** @var Institucion $institucion */

        $institucion = $institucionRepository->find($id);

        $form = $this->createForm(InstitucionType::class, $institucion, [
            'action' => $this->generateUrl('ie#detalle-ie', [
                'id' => $institucion->getId()
            ]),
        ]);

        $solicitud = $solicitudRepository->getSolicitudesByInstitucion($id);

        $convenios = $convenioRepository->getConveniosUnicosByInstitucionId(
            $institucion->getId()
        );

        return $this->render('fofoe/detalle_ie/index.html.twig', [
            'convenios' => $institucionPerfilNormalizer->normalizeConvenios($convenios),
            'institucion' => $institucionPerfilNormalizer->normalizeInstitucion($institucion),
            'errores' => $this->getFormErrors($form),
            'pagos' => $this->getNormalizePagos($solicitud)
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
                    'noSolicitud',
                    'referenciaBancaria',
                    'estatus',
                    'tipoPago',
                    'fecha',
                    'pagos' => [
                        'id',
                        'fechaPagoFormatted',
                        'referenciaBancaria',
                        'monto',
                        'requiereFactura',
                        'validado',
                        'factura' => [
                          'zip'
                        ]
                    ]
                ]
            ]
        );
    }
}
