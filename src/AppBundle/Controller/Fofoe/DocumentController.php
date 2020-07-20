<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Pago;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Repository\PagoRepositoryInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fofoe")
 */
final class DocumentController extends DIEControllerController
{
    /**
     * @Route("/pagos/{id}/descargar-comprobante-de-pago", name="fofoe#descargar_comprobante_de_pago")
     * @param $id
     * @param PagoRepositoryInterface $pagoRepository
     * @param InstitucionRepositoryInterface $institucionRepository
     * @return Response
     */
    public function descargarComprobanteDePago(
        $id,
        PagoRepositoryInterface $pagoRepository,
        InstitucionRepositoryInterface $institucionRepository
    ) {
        /** @var Pago $pago */
        $pago = $pagoRepository->find($id);
        if(!$pago) throw $this->createNotFindPagoException($id);

        $institucion = $institucionRepository->getInstitucionByPagoId($pago->getId());

        return $this->pdfResponse(
            sprintf(
                '../uploads/instituciones/%s/%s',
                $institucion->getId(),
                $pago->getComprobantePago()
            )
        );
    }

    private function pdfResponse($fileName, $contentDisposition = 'attachment')
    {
        $contentDispositionDirectives = [ResponseHeaderBag::DISPOSITION_INLINE, ResponseHeaderBag::DISPOSITION_ATTACHMENT];
        if (!in_array($contentDisposition, $contentDispositionDirectives)) {
            throw new \InvalidArgumentException(sprintf('Expected one of the following directives: "%s", but "%s" given.', implode('", "', $contentDispositionDirectives), $contentDisposition));
        }

        $response = new BinaryFileResponse($fileName);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }
}
