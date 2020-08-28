<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Institucion;
use AppBundle\Event\InstitucionEvent;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

/**
 * Class SolicitudSubscriber
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

    if (!$file) {
      $this->logDB('Ocurrió un error al intentar cargar la cédula de la IE.',
        [
        'institucion_id' => $institucion->getId(),
        'cedula' => $institucion->getCedulaIdentificacion()
      ], 'error'
      );
      return;
    }

    $this->logDB('Archivo cargado. Cédula de identificación actualizada', [
      'institucion_id' => $institucion->getId(),
      'cedula' => $institucion->getCedulaIdentificacion(),
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