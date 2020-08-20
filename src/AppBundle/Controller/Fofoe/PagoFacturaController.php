<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Factura;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Form\Type\ComprobantePagoType\SolicitudFacturaType as ComprobantePagoTypeSolicitudFacturaType;
use AppBundle\Form\Type\FacturaType\PagoFacturaType;
use AppBundle\Form\Type\FacturaType\SolicitudFacturaType;
use AppBundle\Form\Type\PagoType;
use AppBundle\Repository\EstatusCampoRepository;
use AppBundle\Repository\EstatusCampoRepositoryInterface;
use ClassesWithParents\F;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/fofoe")
 */
class PagoFacturaController extends DIEControllerController
{
    /**
     * @Route("/pagos/{id}/registrar-factura", name="fofoe#registrar_factura", methods={"POST", "GET"})
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function registrarFacturaAction(
        $id,
        Request $request,
        EntityManagerInterface $entityManager,
        InstitucionRepositoryInterface $institucionRepository,
        EstatusCampoRepositoryInterface $campoRepository,
        PagoRepositoryInterface $pagoRepository
    ) {

        $pago = $pagoRepository->find($id);

        $institucion = $institucionRepository->getInstitucionBySolicitudId($pago->getSolicitud()->getId());

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($pago->getSolicitud()->getId());

        $pagos = $pagoRepository->getComprobantesPagoValidadosByReferenciaBancaria($pago->getReferenciaBancaria());

        $form = $this->createForm(PagoFacturaType::class, $pago, [
            'action' => $this->generateUrl('fofoe#registrar_factura', [
                'id' => $id,
            ]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        var_dump($form->isSubmitted());
        var_dump($form->isValid());
        if ($form->isSubmitted() && $form->isValid()) {
            $pago = $form->getData();
            $factura = $pago->getFactura();

            $pagos = $pagoRepository->getComprobantesPagoValidadosByReferenciaBancaria($pago->getReferenciaBancaria());

            foreach($pagos as $pagoV) {
                $pagoV->setFacturaGenerada(true);
                $factura->addPago($pagoV);
                $pagoV->setFactura($factura);
            }

            $campos = $pago->getCamposPagados();
            foreach($campos['campos'] as $campoV) {
              $campoV->setEstatus(
                $campoRepository->findOneBy(
                  ['nombre' => EstatusCampoInterface::CREDENCIALES_GENERADAS])
              );
            }

            $solicitud = $pago->getSolicitud();
          if($solicitud->isPagoUnico() ||
            (count(array_filter($solicitud->getCamposClinicos()->toArray(),
                function (CampoClinico $campoClinico) {
                    $estatus =$campoClinico->getEstatus();
                    return $estatus && $estatus->getNombre() !== EstatusCampoInterface::CREDENCIALES_GENERADAS;
              })) === 0 ) ) {
            $solicitud->setEstatus(SolicitudInterface::CREDENCIALES_GENERADAS);
          }

          $entityManager->persist($factura);
            $entityManager->flush();

            $this->addFlash('success',
              sprintf('Se ha guardado correctamente la factura con folio %s 
              para la solicitud %s con número de referencia %s',
                $factura->getFolio(),
                $solicitud->getNoSolicitud(),
                $pago->getReferenciaBancaria()));

            return $this->redirectToRoute('fofoe/inicio');
        }

        return $this->render('fofoe/registrar_factura.html.twig', [
            'institucion' => $this->getNormalizeInstitucion($institucion),
            'solicitud' => $this->getNormalizeSolicitud($solicitud),
            'pagos' => $this->getNormalizePago($pagos)
        ]);
    }

    private function getNormalizeInstitucion($institucion)
    {
        return $this->get('serializer')->normalize(
            $institucion,
            'json',
            [
                'attributes' => [
                    'id',
                    'nombre',
                    'rfc',
                    'cedulaIdentificacion'
                ]
            ]
        );
    }

    private function getNormalizeSolicitud($solicitud)
    {
        return $this->get('serializer')->normalize(
            $solicitud,
            'json',
            [
                'attributes' => [
                    'id',
                    'noSolicitud',
                    'fecha',
                    'referenciaBancaria',
                    'monto',
                    'delegacion' => ['nombre'],
                    'pagos' => [
                        'id',
                        'monto',
                        'fechaPago',
                        'fechaPagoFormatted',
                        'comprobantePago',
                        'requiereFactura',
                        'facturaGenerada',
                        'validado',
                        'referenciaBancaria',
                        'factura' => [
                            'fechaFacturacion',
                            'folio',
                            'zip',
                            'monto'
                        ]
                    ]
                ]
            ]
        );
    }

    private function getNormalizePago($pago)
    {
        return $this->get('serializer')->normalize(
            $pago,
            'json',
            [
                'attributes' => [
                    'id',
                    'monto',
                    'fechaPago',
                    'fechaPagoFormatted',
                    'comprobantePago',
                    'requiereFactura',
                    'facturaGenerada',
                    'validado',
                    'referenciaBancaria',
                    'factura' => [
                        'fechaFacturacion',
                        'folio',
                        'zip',
                        'monto'
                    ],
                    'camposPagados' => [
                      'monto',
                      'convenio' => [
                        'delegacion' => [
                          'nombre'
                        ]],
                    ]
                ]
            ]
        );
    }



    public function indexAction(Request $request)
    {
        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $solicitudes = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->getSolicitudesPagadas($perPage, $page, $request->query->all());
        return $this->render('fofoe/solicitud/index.html.twig', [
            'solicitudes' => $this->get('serializer')->normalize(
                $solicitudes['data'],
                'json',
                [
                    'attributes' => [
                        'id', 'noSolicitud', 'estatus', 'tipoPago',
                        'delegacion' => ['id', 'nombre'],
                        'institucion' => ['id', 'nombre'],
                        'pagos' => ['id', 'referenciaBancaria', 'validado', 'requiereFactura', 'fechaPagoFormatted',
                            'factura' => ['id', 'folio']]
                    ]
                ]
            ),
            'meta' => ['total' => $solicitudes['total'], 'perPage' => $perPage, 'page' => $page]
        ]);

    }

    /**
     * @Route("/api/solicitud", methods={"GET"}, name="fofoe.solicitud.index.api")
     */
    public function indexApiAction(Request $request)
    {
        $perPage = $request->query->get('perPage', 10);
        $page = $request->query->get('page', 1);
        $solicitudes = $this->getDoctrine()
            ->getRepository(Solicitud::class)
            ->getSolicitudesPagadas($perPage, $page, $request->query->all());
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize(
                $solicitudes['data'],
                'json',
                [
                    'attributes' => [
                        'id', 'noSolicitud', 'estatus', 'tipoPago',
                        'delegacion' => ['id', 'nombre'],
                        'institucion' => ['id', 'nombre'],
                        'pagos' => ['id', 'referenciaBancaria', 'validado', 'requiereFactura', 'fechaPagoFormatted',
                            'factura' => ['id', 'folio']]
                    ]
                ]
            ),
            'meta' => ['total' => $solicitudes['total'], 'perPage' => $perPage, 'page' => $page]
        ]);
    }

    public function update(Factura $pago, EntityManagerInterface $entityManager)
    {
        

        $file = $pago->getZipFile();
        $pago->setZipFile(null);
        $entityManager->persist($pago);

        $pago->setZipFile($file);
        $entityManager->flush();

        return true;
    }
}
