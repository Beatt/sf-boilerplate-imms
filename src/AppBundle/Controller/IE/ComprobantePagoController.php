<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Pago;
use AppBundle\Form\Type\ComprobantePagoType\ComprobantePagoType;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Security\Voter\SolicitudVoter;
use AppBundle\Service\UploaderComprobantePagoInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ie")
 */
class ComprobantePagoController extends DIEControllerController
{
    /**
     * @Route("/pagos/{id}/cargar-comprobante-de-pago", name="ie#cargar_comprobante_de_pago", methods={"POST"})
     * @param int $id
     * @param Request $request
     * @param UploaderComprobantePagoInterface $uploaderComprobantePago
     * @param PagoRepositoryInterface $pagoRepository
     * @return RedirectResponse
     */
    public function cargarComprobanteDePagoAction(
        $id,
        Request $request,
        UploaderComprobantePagoInterface $uploaderComprobantePago,
        PagoRepositoryInterface $pagoRepository
    ) {
        /** @var Pago $pago */
        $pago = $pagoRepository->find($id);
        if(!$pago) throw $this->createNotFindPagoException($id);

        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        $this->denyAccessUnlessGranted(SolicitudVoter::CARGAR_COMPROBANTE_DE_PAGO, $pago->getSolicitud());

        $form = $this->createForm(ComprobantePagoType::class, $pago, [
            'action' => $this->generateUrl('ie#cargar_comprobante_de_pago', [
                'id' => $id
            ]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            /** @var Pago $pago */
            $pago = $form->getData();
            $uploaderComprobantePago->update($pago);

            $this->addFlash('success', '¡El comprobante se ha cargado correctamente!');
            return $this->redirectToRoute('ie#inicio');
        }

        $this->addFlash('success', '¡Lo sentimos! Ha ocurrido un problema al cargar tu comprobante.');
        return $this->redirectToRoute('ie#inicio');
    }
}
