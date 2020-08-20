<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Factura;
use AppBundle\Entity\Pago;
use AppBundle\Exception\CouldNotFoundCedulaIdentificacionFiscal;
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

      $downloadHandler = $this->get('vich_uploader.download_handler');
      return $downloadHandler->downloadObject($pago, 'comprobantePagoFile');
    }

    /**
     * @Route("/instituciones/{id}/descargar-cedula-de-identificacion", name="fofoe#descargar_Cedula_Identificacion")
     * @param $id
     * @param InstitucionRepositoryInterface $institucionRepository
     * @return Response
     */
    public function descargarCedulaIdentificacionFiscal(
        $id,
        InstitucionRepositoryInterface $institucionRepository
    ) {
        /** @var Institucion $institucion */
        $institucion = $institucionRepository->findOneBy([
            'id' => $id
        ]);


      if (!$institucion)
        throw $this->createNotFoundException(
          'Not found for id ' . $id
        );

      if(!$institucion->getCedulaIdentificacion())
        throw CouldNotFoundCedulaIdentificacionFiscal::withInstitucionId($institucion->getId());

      $downloadHandler = $this->get('vich_uploader.download_handler');
      return $downloadHandler->downloadObject($institucion, 'cedulaFile');
    }

  /**
   * @Route("/factura/{factura_id}/download", methods={"GET"}, name="factura.download")
   * @param $factura_id
   * @return mixed
   */
  public function downloadFileAction($factura_id)
  {
    $factura = $this->getDoctrine()
      ->getRepository(Factura::class)
      ->find($factura_id);

    if (!$factura) {
      throw $this->createNotFoundException(
        'Not found for id ' . $factura_id
      );
    }
    $downloadHandler = $this->get('vich_uploader.download_handler');
    return $downloadHandler->downloadObject($factura, 'zipFile');
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
