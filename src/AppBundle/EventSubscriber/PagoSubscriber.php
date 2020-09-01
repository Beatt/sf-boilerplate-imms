<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Pago;
use AppBundle\Event\PagoEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

/**
 * Class PagoSubscriber
 * @package AppBundle\EventSubscriber
 */
class PagoSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
    return [
      Events::POST_UPLOAD => 'onComprobanteCargado',
      PagoEvent::PAGO_VALIDADO => 'onPagoValidado',
      PagoEvent::PAGO_INCORRECTO => 'onPagoIncorrecto'
    ];
  }

  public function onComprobanteCargado(Event $event)
  {
    if (! $event->getObject() instanceof Pago) return;

    $pago = $event->getObject();;

    $requestFile = $this->request->files->get('comprobante_pago')['comprobantePagoFile'];
    $original_filename = $requestFile->getClientOriginalName();
    $original_filename = strlen($original_filename) > 35 ?
      substr($original_filename, 0, 30) . "..." : $original_filename;

    $file = $pago->getComprobantePagoFile();

    if (!$file) {
      $this->logDB('Ocurrió un error al intentar cargar el comprobante de pago.',
        array_merge( $this->getDataPago($pago),
          ['comprobante' => $pago->getComprobantePago(),
            'file_name' => $original_filename,
            'requiere_factura' => $pago->isRequiereFactura()
          ])
        , 'error'
      );
      return;
    }

    $this->logDB('Archivo cargado. Comprobante de pago.',
      array_merge( $this->getDataPago($pago),
        [
          'monto' => $pago->getMonto(),
          'fecha_pago' => $pago->getFechaPago()->format('Y-m-d'),
          'comprobante' => $pago->getComprobantePago(),
          'file_name' => $original_filename,
          'type' => $file->getMimeType(),
          'size' => number_format($file->getSize()/1024.0, 2)." Kb"
        ])
      );
  }

  public function onPagoIncorrecto(PagoEvent $event)
  {
    $pago = $event->getPago();
    $this->logDB('Se ha registrado que el comprobante de pago NO es válido.',
      array_merge( $this->getDataPago($pago),
        ['monto' => $pago->getMonto(),
            'fecha_pago' => $pago->getFechaPago()->format('Y-m-d'),
          'observaciones' => $pago->getObservaciones()
        ])
    );
  }

  public function onPagoValidado(PagoEvent $event)
  {
    $pago = $event->getPago();

    $this->logDB('Se ha confirmado que el comprobante de pago es válido.',
      array_merge( $this->getDataPago($pago),
        ['monto' => $pago->getMonto(),
          'fecha_pago' => $pago->getFechaPago()->format('Y-m-d')
        ])
    );
  }

  private function getDataPago($pago) {
    $solicitud = $pago->getSolicitud();
    return [
      'pago_id' => $pago->getId(),
      'solicitud_id' => $solicitud->getId(),
      'tipo_pago' => $solicitud->getTipoPago(),
      'referenciaBancaria' => $pago->getReferenciaBancaria(),
    ];

  }

}