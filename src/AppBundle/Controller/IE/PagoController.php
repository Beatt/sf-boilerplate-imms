<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Security\Voter\SolicitudVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/ie")
 */
class PagoController extends DIEControllerController
{
    /**
     * @Route("/pagos/{id}/gestion-de-pago", name="ie#obtener_gestion_de_pagos")
     * @param int $id
     * @param PagoRepositoryInterface $pagoRepository
     * @param NormalizerInterface $normalizer
     * @param SolicitudRepositoryInterface $solicitudRepository
     * @return JsonResponse
     */
    public function obtenerGestionPago(
        $id,
        PagoRepositoryInterface $pagoRepository,
        NormalizerInterface $normalizer,
        SolicitudRepositoryInterface $solicitudRepository
    ) {
        /** @var Pago $pago */
        $pago = $pagoRepository->find($id);
        if(!$pago) throw new \InvalidArgumentException('El pago no existe');

        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFoundInstitucionException();

        /** @var Solicitud $solicitud */
        $solicitud = $solicitudRepository->find($pago->getSolicitud()->getId());
        if(!$solicitud) throw $this->createNotFoundSolicitudException();

        $this->denyAccessUnlessGranted(SolicitudVoter::OBTENER_GESTION_DE_PAGOS, $solicitud);

        return new JsonResponse($normalizer->normalize(
            $pago->getGestionPago(),
            'json', [
                'attributes' => [
                    'noSolicitud',
                    'montoTotal',
                    'montoTotalPorPagar',
                    'tipoPago',
                    'campoClinico' => [
                        'sede',
                        'carrera'
                    ],
                    'pagos' => [
                        'comprobanteConEnlace',
                        'referenciaBancaria',
                        'fechaPago',
                        'monto'
                    ],
                    'ultimoPago' => [
                        'observaciones'
                    ]
                ]
            ]
        ));
    }
}
