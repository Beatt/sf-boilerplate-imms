<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Event\InstitucionEvent;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class SolicitudSubscriber
 * @package AppBundle\EventSubscriber
 */
class InstitucionSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
    return [
      InstitucionEvent::DATOS_ACTUALIZADOS => 'onDatosActualizados'
    ];
  }

  public function onDatosActualizados(InstitucionEvent $event)
  {
    $institucion = $event->getInstitucion();
    $this->logDB('Actualización de datos de institución educativa', [
      'institucion_id' => $institucion->getId()
    ]);
  }
}