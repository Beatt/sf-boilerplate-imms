<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Event\InstitucionEvent;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class SolicitudSubscriber
 * @package AppBundle\EventSubscriber
 */
class InstitucionSubscriber implements EventSubscriberInterface
{

  /**
   * @var ContainerInterface
   */
  protected $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public static function getSubscribedEvents()
  {
    return [
      InstitucionEvent::DATOS_ACTUALIZADOS => 'onDatosActualizados'
    ];
  }

  public function onDatosActualizados(InstitucionEvent $event)
  {
    $institucion = $event->getInstitucion();
    $this->logDB(SolicitudInterface::MONTOS_VALIDADOS_CAME, [
      'institucion_id' => $institucion->getId()
    ]);
  }
}