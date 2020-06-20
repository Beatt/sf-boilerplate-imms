<?php

namespace AppBundle\Controller\IE;

use AppBundle\Entity\Pago;
use AppBundle\Repository\PagoRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/ie")
 */
class PagoController extends AbstractController
{
    /**
     * @Route("/pagos/gestion-de-pago/{id}", name="ie#obtener_gestion_de_pagos")
     * @param int $id
     * @param PagoRepositoryInterface $pagoRepository
     * @param NormalizerInterface $normalizer
     * @return JsonResponse
     */
    public function obtenerGestionPago(
        $id,
        PagoRepositoryInterface $pagoRepository,
        NormalizerInterface $normalizer
    ) {
        /** @var Pago $pago */
        $pago = $pagoRepository->find($id);

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
