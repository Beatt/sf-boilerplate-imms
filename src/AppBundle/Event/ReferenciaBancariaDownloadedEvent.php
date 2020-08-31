<?php

namespace AppBundle\Event;

use AppBundle\Entity\Solicitud;
use Symfony\Component\EventDispatcher\Event;

class ReferenciaBancariaDownloadedEvent extends Event
{
  const NAME = 'referencia_bancaria.downloaded';

  private $solicitud;

  private $referencia;

  public function __construct(Solicitud $solicitud, $referencia)
  {
    $this->solicitud = $solicitud;
    $this->referencia = $referencia;
  }

  /**
   * @return Solicitud
   */
  public function getSolicitud()
  {
    return $this->solicitud;
  }

  /**
   * @return string
   */
  public function getReferencia()
  {
    return $this->referencia;
  }
  
  
}
