<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Factura;
use AppBundle\Entity\Solicitud;
use AppBundle\Form\Type\FacturaType\FacturaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route('/fofoe')
 */
class SolicitudController extends DIEControllerController
{
    /**
     * @Route("/solicitudes/{id}/registrar-factura", name="fofoe#registrar_factura")
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

        $factura = new Factura();
        $form = $this->createForm(FacturaType::class, $factura, [
            'action' => $this->generateUrl('fofoe#registrar_factura', [
                'id' => $id,
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash('success', 'Se ha guardado correctamente la factura');

            return $this->redirectToRoute('solicitudes#index', [
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
                        'requiereFactura'
                    ]
                ]
            ]
        );
    }
}
