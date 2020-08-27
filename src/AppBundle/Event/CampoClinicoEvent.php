<?php

namespace AppBundle\Event;

use AppBundle\Entity\Solicitud;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CampoClinicoEvent
 * @package AppBundle\Event
 */
class CampoClinicoEvent extends Event
{
  const CAMPO_REGISTRADO = 'campo.registrado';

}