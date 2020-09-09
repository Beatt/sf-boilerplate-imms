<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use AppBundle\Event\ReferenciaBancariaDownloadedEvent;
use AppBundle\Event\SolicitudEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

/**
* Class SolicitudSubscriber
* @package AppBundle\EventSubscriber
*/
class SolicitudSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
    return [
      SolicitudEvent::SOLICITUD_CREADA => 'onSolicitudCreada',
      SolicitudEvent::SOLICITUD_TERMINADA => 'onSolicitudTerminada',
      SolicitudEvent::MONTOS_REGISTRADOS => 'onMontosRegistrados',
      SolicitudEvent::MONTOS_INCORRECTOS => 'onMontosIncorrectos',
      SolicitudEvent::MONTOS_VALIDADOS => 'onMontosValidados',
      Events::POST_UPLOAD => 'onOficioCargado',
      SolicitudEvent::FORMATOS_GENERADOS => 'onFormatosGenerados',
      ReferenciaBancariaDownloadedEvent::NAME => 'onReferenciaDownloaded',
    ];
  }

  public function onSolicitudCreada(SolicitudEvent $event)
  {
    $this->logDB(SolicitudInterface::CREADA, [
      'solicitud_id' => $event->getSolicitud()->getId()
    ]);
  }

  public function onSolicitudTerminada(SolicitudEvent $event)
  {
    $solicitud = $event->getSolicitud();
    $this->logDB(SolicitudInterface::CONFIRMADA, [
      'solicitud_id' => $solicitud->getId(),
      'numCamposSolicitados' => $solicitud->getNoCamposSolicitados(),
      'numCamposAutorizados' => $solicitud->getNoCamposAutorizados()
    ]);
  }

  public function onMontosRegistrados(SolicitudEvent $event)
  {
    $solicitud = $event->getSolicitud();

    $this->logDB(
      'Se han registrado los montos de inscripción y colegiatura de la IE',
      [
      'solicitud_id' => $solicitud->getId(),
        'oficio' => $solicitud->getUrlArchivo(),
        'estatus' => $solicitud->getEstatus()
    ]);
  }

  public function onMontosIncorrectos(SolicitudEvent $event)
  {
    $solicitud = $event->getSolicitud();
    $this->logDB('Se ha registrado que los montos o el oficio registrado NO son válidos', [
      'solicitud_id' => $solicitud->getId(),
      'observaciones' => $solicitud->getObservaciones()
    ]);
  }

  public function onMontosValidados(SolicitudEvent $event)
  {
    $solicitud = $event->getSolicitud();
    $this->logDB('Se ha confirmado que los montos y el oficio registrado son válidos', [
      'solicitud_id' => $solicitud->getId()
    ]);
  }

  public function onOficioCargado(Event $event)
  {
    if (! $event->getObject() instanceof Solicitud) return;
    $solicitud = $event->getObject();
    $file = $solicitud->getUrlArchivoFile();

    $requestFile = $this->request->files->get('solicitud_validacion_montos')['urlArchivoFile'];
    $original_filename = $requestFile->getClientOriginalName();
    $original_filename = strlen($original_filename) > 35 ?
      substr($original_filename, 0, 30) . "..." : $original_filename;

    if (!$file) {
      $this->logDB(
        'Ocurrió un error al intentar cargar el oficio de montos de inscripción y colegiatura de la IE',
        [
          'solicitud_id' => $solicitud->getId(),
          'oficio' => $solicitud->getUrlArchivo(),
          'file_name' => $original_filename,
        ], 'error'
      );
      return;
    }

    $this->logDB('Archivo cargado: Oficio de montos de inscripción y colegiatura', [
      'solicitud_id' => $solicitud->getId(),
      'oficio' => $solicitud->getUrlArchivo(),
      'file_name' => $original_filename,
      'type' => $file->getMimeType(),
      'size' => number_format($file->getSize()/1024.0, 2)." Kb"
    ]);
  }

  public function onFormatosGenerados(SolicitudEvent $event)
  {
    $solicitud = $event->getSolicitud();
    $this->logDB('Se ha seleccionado la modalidad de pago. Formatos de pago generados', [
      'solicitud_id' => $solicitud->getId(),
      'tipo_pago' => $solicitud->getTipoPago()
    ]);
  }

  public function onReferenciaDownloaded(ReferenciaBancariaDownloadedEvent $event)
  {
    $solicitud = $event->getSolicitud();
    $esPagoUnico =$solicitud->getTipoPago() == SolicitudTipoPagoInterface::TIPO_PAGO_UNICO;
    $msg = 'Referencia(s) de pago descargada(s) ';
    if ($esPagoUnico) {
      $this->logDB($msg, [
        'solicitud_id' => $solicitud->getId(),
        'tipo_pago' => $solicitud->getTipoPago(),
        'estatus' => $solicitud->getEstatus(),
        'referencia' => $solicitud->getReferenciaBancaria(),
        'pago_id' => $solicitud->getPago()->getId()
    ]);
    } else {
      foreach ($solicitud->getPagos() as $pago) {
        $this->logDB($msg, [
          'solicitud_id' => $solicitud->getId(),
          'tipo_pago' => $solicitud->getTipoPago(),
          'referencia' => $pago->getReferenciaBancaria(),
          'pago_id' => $pago->getId()
        ]);
      }
    }
  }

}