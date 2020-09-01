<?php

namespace AppBundle\Controller\IE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\Factura;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Exception\CouldNotFoundCedulaIdentificacionFiscal;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use AppBundle\Security\Voter\SolicitudVoter;
use AppBundle\Service\GeneradorFormatosFofoeZIPInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ie")
 */
final class DocumentController extends DIEControllerController
{
    private $solicitudRepository;

    public function __construct(SolicitudRepositoryInterface $solicitudRepository)
    {
        $this->solicitudRepository = $solicitudRepository;
    }

  /**
   * @Route("/solicitud/{id}/descargar-comprobante-inscripcion", name="ie#descargar_comprobante_inscripcion")
   * @param int $id
   * @return Response
   */
  public function descargarComprobanteInscripcion(
    $id
  ) {
    /** @var Institucion $institucion */
    $institucion = $this->getUser()->getInstitucion();
    if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

    /** @var Solicitud $pago */
    $solicitud = $this->solicitudRepository->find($id);
    if(!$solicitud) throw $this->createNotFindSolicitudException($id);

    $downloadHandler = $this->get('vich_uploader.download_handler');
    return $downloadHandler->downloadObject($solicitud, 'urlArchivoFile');
  }

    /**
     * @Route("/pagos/{id}/descargar-comprobante-de-pago", name="ie#descargar_comprobante_de_pago")
     * @param int $id
     * @param PagoRepositoryInterface $pagoRepository
     * @return Response
     */
    public function descargarComprobanteDePago(
        $id,
        PagoRepositoryInterface $pagoRepository
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        /** @var Pago $pago */
        $pago = $pagoRepository->find($id);
        if(!$pago) throw $this->createNotFindPagoException($id);

        $this->denyAccessUnlessGranted(SolicitudVoter::DESCARGAR_COMPROBANTE_DE_PAGO, $pago->getSolicitud());

      $downloadHandler = $this->get('vich_uploader.download_handler');
      return $downloadHandler->downloadObject($pago, 'comprobantePagoFile');
    }

    /**
     * @Route("/descargar-cedula-de-identificacion-fiscal", name="ie#descargar_cedula_de_identificacion_fiscal")
     */
    public function descargarCedulaDeIdentificacionFiscal()
    {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        if(!$institucion->getCedulaIdentificacion()) throw CouldNotFoundCedulaIdentificacionFiscal::withInstitucionId($institucion->getId());

        $downloadHandler = $this->get('vich_uploader.download_handler');
        return $downloadHandler->downloadObject($institucion, 'cedulaFile');
    }

    /**
     * @Route("/solicitudes/{id}/descargar-formatos-fofoe", name="ie#descargar_formatos_fofoe")
     * @param $id
     * @param GeneradorFormatosFofoeZIPInterface $generadorFormatosFofoeZIP
     */
    public function descargarFormatosFofoe(
        $id,
        GeneradorFormatosFofoeZIPInterface $generadorFormatosFofoeZIP
    ) {
        /** @var Institucion $institucion */
        $institucion = $this->getUser()->getInstitucion();
        if(!$institucion) throw $this->createNotFindUserRelationWithInstitucionException();

        /** @var Solicitud $solicitud */
        $solicitud = $this->solicitudRepository->find($id);
        if(!$solicitud) throw $this->createNotFindSolicitudException($id);

        $this->denyAccessUnlessGranted(SolicitudVoter::DESCARGAR_FORMATOS_FOFOE, $solicitud);

        return $generadorFormatosFofoeZIP->generarZipResponse($solicitud);
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

  /**
   * @Route("/factura/{factura_id}/download", methods={"GET"}, name="ie.descargar.factura.download")
   * @param $factura_id
   * @return mixed
   */
  public function descargarFacturaAction($factura_id)
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
}
