<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Factura;
use AppBundle\Entity\Solicitud;
use AppBundle\Form\Type\ComprobantePagoType\SolicitudFacturaType as ComprobantePagoTypeSolicitudFacturaType;
use AppBundle\Form\Type\FacturaType\PagoFacturaType;
use AppBundle\Form\Type\FacturaType\SolicitudFacturaType;
use AppBundle\Form\Type\PagoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ie")
 */
class SolicitudController extends DIEControllerController
{
    /**
     * @Route("/solicitudes/{id}/registrar-factura", name="fofoe#registrar_factura", methods={"POST", "GET"})
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function registrarFacturaAction(
        $id,
        Request $request,
        EntityManagerInterface $entityManager
    ) {

        $institucion = $this->getUser()->getInstitucion();

        /** @var Solicitud $solicitud */
        $solicitud = $this->get('doctrine')->getRepository(Solicitud::class)
            ->find($id);

        //$factura = new Factura();
        $form = $this->createForm(SolicitudFacturaType::class, $solicitud, [
            'action' => $this->generateUrl('fofoe#registrar_factura', [
                'id' => $id,
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Solicitud $solicitud */
            $solicitud = $form->getData();

            $factura = new Factura();
            
            $pagos = $solicitud->getPagos();

            $factura = $pagos[0]->getFactura();

            foreach($solicitud->getPagos() as $pago){
                if($pago->isFacturaGenerada())
                    $pago->setFactura($factura);
                else
                    $pago->setFactura(null);
            }

            $entityManager->persist($solicitud);
            $entityManager->flush();

            $this->addFlash('success', 'Se ha guardado correctamente la factura');

            return $this->redirectToRoute('ie#inicio', [
                'id' => $id
            ]);
        }

        return $this->render('fofoe/registrar_factura.html.twig', [
            'institucion' => $this->getNormalizeInstitucion($institucion),
            'solicitud' => $this->getNormalizeSolicitud($solicitud)
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
                    'rfc'
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
                    'estatus',
                    'fecha',
                    'montosCarrera' => [
                        'montoInscripcion',
                        'montoColegiatura',
                        'carrera'
                    ],
                    'observaciones',
                    'referenciaBancaria',
                    'monto',
                    'pagos' => [
                        'id',
                        'monto',
                        'fechaPago',
                        'comprobantePago',
                        'requiereFactura',
                        'facturaGenerada',
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


    /**
     * @Route("/inicio", methods={"GET"}, name="fofoe/inicio")
     *
     */
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
}
