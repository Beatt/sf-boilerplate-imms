<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Factura;
use AppBundle\EventSubscriber\AbstractSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

/**
 * Class FacturaSubscriber
 * @package AppBundle\EventSubscriber
 */
class FacturaSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
    return [
      Events::POST_UPLOAD => 'onFacturaCargada'
    ];
  }

  public function onFacturaCargada(Event $event)
  {
    if (! $event->getObject() instanceof Factura) return;
    $factura = $event->getObject();

    $pago = $factura->getPago();
    $referencia = $pago ? $pago->getReferenciaBancaria() : '';
    $solicitud_id = $pago ? $pago->getSolicitud()->getId() : '';

    $requestFile = $this->request->files->get('pago_factura')['factura']['zipFile'];

    $original_filename = $requestFile->getClientOriginalName();
    $original_filename = strlen($original_filename) > 35 ?
      substr($original_filename, 0, 30) . "..." : $original_filename;

    $file = $factura->getZipFile();

    $pagosId = "";
    foreach ($factura->getPagos() as $p) {
      $pagosId .= $p->getId() . " , ";
    }
    $pagosId = strlen($pagosId) > 3 ? mb_substr($pagosId, 0, -3) : $pagosId;

    if (!$file) {
      $this->logDB('Ocurrió un error al intentar cargar el comprobante de pago.',
        array_merge( $this->getDataPago($pago),
          ['factura_id' => $factura->getId(),
            'solicitud_id' => $solicitud_id,
            'monto_factura' => $factura->getMonto(),
            'fecha_facturacion' => $factura->getFechaFacturacion()->format('Y-m-d'),
            'zip' => $factura->getZip(),
            'file_name' => $original_filename
          ])
        , 'error'
      );
      return;
    }

    $this->logDB('Se ha registrado la factura para el pago.',
        [
          'factura_id' => $factura->getId(),
          'solicitud_id' => $solicitud_id,
          'monto_factura' => $factura->getMonto(),
          'fecha_facturacion' => $factura->getFechaFacturacion()->format('Y-m-d'),
          'referencia_bancaria' => $referencia,
          'folio' => $factura->getFolio(),
          'pagos_id' => $pagosId,
          'zip' => $factura->getZip(),
          'file_name' => $original_filename,
          'type' => $file->getMimeType(),
          'size' => number_format($file->getSize()/1024.0, 2)." Kb"
        ]
    );
  }

}