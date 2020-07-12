<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Solicitud;
use AppBundle\ObjectValues\SolicitudId;
use AppBundle\Repository\IE\DetalleSolicitudMultiple\DetalleSolicitudMultiple;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Security\Voter\SolicitudVoter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/ie")
 */
class CampoClinicoController extends DIEControllerController
{
    /**
     * @Route("/solicitudes/{id}/detalle-de-solicitud-multiple", name="ie#detalle_de_solicitud_multiple")
     * @param $id
     * @param SolicitudRepositoryInterface $solicitudRepository
     * @param DetalleSolicitudMultiple $detalleSolicitudMultiple
     * @param NormalizerInterface $normalizer
     * @return Response
     */
    public function indexAction(
        $id,
        SolicitudRepositoryInterface $solicitudRepository,
        DetalleSolicitudMultiple $detalleSolicitudMultiple,
        NormalizerInterface $normalizer
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        /** @var Solicitud $solicitud */
        $solicitud = $solicitudRepository->find($id);
        if(!$solicitud) throw $this->createNotFindSolicitudException($id);

        $this->denyAccessUnlessGranted(SolicitudVoter::DETALLE_DE_SOLICITUD_MULTIPLE, $solicitud);

        $solicitud = $detalleSolicitudMultiple->getDetalleBySolicitud(
            SolicitudId::fromString($solicitud->getId())
        );

        return $this->render('ie/campo_clinico/detalle_de_solicitud_multiple.html.twig', [
            'solicitud' => $normalizer->normalize($solicitud)
        ]);
    }
}
