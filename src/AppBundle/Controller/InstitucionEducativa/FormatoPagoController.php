<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Repository\CampoClinicoRepository;
use AppBundle\Repository\InstitucionRepositoryInterface;
use AppBundle\Repository\SolicitudRepositoryInterface;
use Proxies\__CG__\AppBundle\Entity\Solicitud;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormatoPagoController extends DIEControllerController
{
  /**
   * @Route("/instituciones/{institucionId}/solicitudes/{solicitudId}/formato-pago",
   *   name="formato-pago#index")
   * @param int $institucionId
   * @param int $solicitudId
   * @return Response
   */
  public function indexPagoUnicoAction($institucionId, $solicitudId,
                              SolicitudRepositoryInterface $solicitudRepository) {

    $solicitud = $solicitudRepository->find($solicitudId);

    $institucion = $solicitud ? $solicitud->getInstitucion() : null;

    $institucion = $institucion && $institucion->getId() == $institucionId ?
      $institucion : null;
    $solicitud = $institucion ? $solicitud : null;
    $campos = $solicitud && $solicitud->getTipoPago() == Solicitud::TIPO_PAGO_UNICO
      ? $solicitud->getCamposClinicos() : null;

    $esPagoUnico =  $solicitud->getTipoPago() == Solicitud::TIPO_PAGO_UNICO ? true : false;
    $esPagoUnico = false;

    return $this->render('institucion_educativa/formatos/ReferenciaPago.html.twig',
      ['institucion' => $this->getNormalizeInstitucion($institucion),
        'solicitud' => $this->getNormalizeSolicitud($solicitud) ,
      'campos' => $this->getNormalizeCampos($campos),
      'esPagoUnico' => $esPagoUnico]);
  }

  /**
   * @Route("/instituciones/{institucionId}/solicitudes/{solicitudId}/formato-pago/{campoId}",
   *   name="formato-pago#multiple")
   * @param int $institucionId
   * @param int $solicitudId
   * @param int $campoId
   * @return Response
   */
  public function indexPagoMultipleAction($institucionId, $solicitudId, $campoId,
                                       InstitucionRepositoryInterface $institucionRepository,
                                       SolicitudRepositoryInterface $solicitudRepository,
                                        CampoClinicoRepository $campoClinicoRepository) {
    $institucion = $institucionRepository->find($institucionId);

    $campo = $campoClinicoRepository->getAllCamposClinicosByRequest($campoId, 0, true);
    $solicitud = $solicitudRepository->find($solicitudId);

    return $this->render('institucion_educativa/formatos/ReferenciaPago.html.twig',
      ['institucion' => $this->getNormalizeInstitucion($institucion),
        'solicitud' => $this->getNormalizeSolicitud($solicitud) ,
        'campos' => $this->getNormalizeCampos( $campo )]);
  }

  private function getNormalizeInstitucion($institucion) {
    return $this->get('serializer')->normalize($institucion, 'json',
      ['attributes' => [
        'id',
        'nombre',
        'rfc'
      ]]);
  }

  private function getNormalizeSolicitud($solicitud) {
    return $this->get('serializer')->normalize($solicitud, 'json',
      [ 'attributes' => [
        'id',
        'noSolicitud',
        'monto',
        'tipoPago'
      ]]);
  }

  private function getNormalizeCampos($campos) {
    return $this->get('serializer')->normalize($campos, 'json',
    [ 'attributes' => [
      'fechaInicial',
      'fechaFinal',
      'lugaresAutorizados',
      'referenciaBancaria',
      'monto',
      'estatus' => ['id', 'nombre'],
      'unidad' => ['nombre'],
      'nombreCicloAcademico',
      'displayCarrera'
    ]]);
  }
}