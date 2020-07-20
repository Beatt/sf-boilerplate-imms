<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Pago;
use AppBundle\Repository\PagoRepositoryInterface;
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
     * @return JsonResponse
     */
    public function obtenerGestionPago(
        $id,
        PagoRepositoryInterface $pagoRepository,
        NormalizerInterface $normalizer
    ) {
        /** @var Pago $pago */
        $pago = $pagoRepository->find($id);
        if(!$pago) throw $this->createNotFindPagoException($id);

        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        $this->denyAccessUnlessGranted(SolicitudVoter::OBTENER_GESTION_DE_PAGOS, $pago->getSolicitud());

        return new JsonResponse($normalizer->normalize($pago->getGestionPago(), 'json'));
    }
}
