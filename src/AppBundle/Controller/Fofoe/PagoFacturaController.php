<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\CampoClinico;
use AppBundle\Entity\EstatusCampoInterface;
use AppBundle\Entity\Factura;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Form\Type\FacturaType\PagoFacturaType;
use AppBundle\Repository\EstatusCampoRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;

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
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Pago $pago */
            $pago = $form->getData();
            // TODO: Hacer un refactor para genear un service con esta logica:
            $entityManager->beginTransaction();
            /** @var Factura $factura */
            $factura = $pago->getFactura();
            $factura->addPago($pago);
            $pago->setFacturaGenerada(true);
            $pago->setFactura($factura);
            $file = $factura->getZipFile();
            $factura->setZipFile(null);

            $pagos = $pagoRepository->getComprobantesPagoValidadosByReferenciaBancaria($pago->getReferenciaBancaria());
            foreach($pagos as $pagoV) {
              if ($pago->getId() == $pagoV->getId()) continue;
                $pagoV->setFacturaGenerada(true);
                $factura->addPago($pagoV);
            }

            $campos = $pago->getCamposPagados();
            $statusCredGen = $campoRepository->findOneBy(['nombre' => EstatusCampoInterface::CREDENCIALES_GENERADAS]);
            foreach($campos['campos'] as $campoV) {
              $campoV->setEstatus( $statusCredGen);
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

          $entityManager->persist($pago);
          $entityManager->flush();
          $factura->setZipFile($file);
          $entityManager->persist($factura);
          $entityManager->flush();
          $entityManager->commit();

            $this->addFlash('success',
              sprintf('Se ha guardado correctamente la factura con folio %s 
              para la solicitud %s con número de referencia %s',
                $factura->getFolio(),
                $solicitud->getNoSolicitud(),
                $pago->getReferenciaBancaria()));

            return $this->redirectToRoute('fofoe/inicio');
        }

        if ($this->getFormErrors($form, true)) {
          $this->addFlash('danger', 'Ocurrió un error al procesar el registro. 
          Verifique los datos e intente de nuevo');
        }

        return $this->render('fofoe/registrar_factura.html.twig', [
            'institucion' => $this->getNormalizeInstitucion($institucion),
            'solicitud' => $this->getNormalizeSolicitud($solicitud),
            'pagos' => $this->getNormalizePago($pagos),
            'errors' => $this->getFormErrors($form, true)
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
                    'unidad' => ['nombre', 'esUmae'],
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
                        'id',
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
