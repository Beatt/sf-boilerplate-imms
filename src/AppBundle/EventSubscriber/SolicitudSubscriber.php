<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\SolicitudInterface;
use AppBundle\Entity\SolicitudTipoPagoInterface;
use AppBundle\Event\BankReferencesCreatedEvent;
use AppBundle\Event\SolicitudEvent;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
      SolicitudEvent::FORMATOS_GENERADOS => 'onFormatosGenerados',
      SolicitudEvent::COMPROBANTE_CARGADO => 'onComprobanteCargado',
      SolicitudEvent::COMPROBANTE_VALIDADO => 'onComprobanteValidado',
      BankReferencesCreatedEvent::NAME => 'onReferenciaCreada'
    ];
  }

  public function onComprobanteCargado(SolicitudEvent $event)
  {
  }

  public function onComprobanteValidado(SolicitudEvent $event)
  {
  }

  public function onFormatosGenerados(SolicitudEvent $event)
  {
  }

  public function onMontosIncorrectos(SolicitudEvent $event)
  {
    $solicitud = $event->getSolicitud();
    $this->logDB(SolicitudInterface::MONTOS_VALIDADOS_CAME, [
      'solicitud_id' => $solicitud->getId(),
      'observaciones' => $solicitud->getObservaciones()
    ]);
  }

  public function onMontosRegistrados(SolicitudEvent $event)
  {
    $solicitud = $event->getSolicitud();
    $this->logDB(SolicitudInterface::MONTOS_VALIDADOS_CAME, [
      'solicitud_id' => $solicitud->getId(),
      'file_name' => $solicitud->getUrlArchivo()
    ]);
  }

  public function onMontosValidados(SolicitudEvent $event)
  {
    $solicitud = $event->getSolicitud();
    $this->logDB(SolicitudInterface::MONTOS_VALIDADOS_CAME, [
      'solicitud_id' => $solicitud->getId()
    ]);
  }

  public function onReferenciaCreada(BankReferencesCreatedEvent $event)
  {
    $solicitud = $event->getSolicitud();
    $esPagoUnico =$solicitud->getTipoPago() == SolicitudTipoPagoInterface::TIPO_PAGO_UNICO;
    $msg = 'Referencia de Pago Generada';
    if ($esPagoUnico) {
      $this->logDB($msg, [
        'solicitud_id' => $solicitud->getId(),
        'tipo_pago' => $solicitud->getTipoPago(),
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

}