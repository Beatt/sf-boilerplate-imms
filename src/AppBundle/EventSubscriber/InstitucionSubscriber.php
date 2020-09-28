<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Institucion;
use AppBundle\Event\InstitucionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

/**
 * Class InstitucionSubscriber
 * @package AppBundle\EventSubscriber
 */
class InstitucionSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
    return [
      InstitucionEvent::DATOS_ACTUALIZADOS => 'onDatosActualizados',
      Events::POST_UPLOAD => 'onCedulaUploaded'
    ];
  }

  public function onCedulaUploaded(Event $event)
  {
    if (! $event->getObject() instanceof Institucion) return;

    $institucion = $event->getObject();
    $file = $institucion->getCedulaFile();

    $original_filename = "No Disponible";
    if ($this->request->files->has('institucion')
        && $requestFile = $this->request->files->get('institucion')['cedulaFile']) {
        $requestFile = $this->request->files->get('institucion')['cedulaFile'];
        $original_filename = $requestFile->getClientOriginalName();
    } elseif ($requestFile = $this->request->files->has('comprobante_pago')
        && $requestFile = $this->request->files->get('comprobante_pago')['cedulaFile']) {
        $requestFile = $this->request->files->get('comprobante_pago')['cedulaFile'];
        $original_filename = $requestFile->getClientOriginalName();
    }

    $original_filename = strlen($original_filename) > 35 ?
      substr($original_filename, 0, 30) . "..." : $original_filename;

    if (!$file) {
      $this->logDB('Ocurrió un error al intentar cargar la cédula de la IE',
        [
        'institucion_id' => $institucion->getId(),
        'cedula' => $institucion->getCedulaIdentificacion(),
          'file_name' => $original_filename,
      ], 'error'
      );
      return;
    }

    $this->logDB('Archivo cargado: Cédula de Identificación Fiscal', [
      'institucion_id' => $institucion->getId(),
      'cedula' => $institucion->getCedulaIdentificacion(),
      'file_name' => $original_filename,
      'type' => $file->getMimeType(),
      'size' => number_format($file->getSize()/1024.0, 2)." Kb"
    ]);

  }

  public function onDatosActualizados(InstitucionEvent $event)
  {
    $institucion = $event->getInstitucion();
    $this->logDB('Actualización de datos de institución educativa', [
      'institucion_id' => $institucion->getId()
    ]);
  }
}