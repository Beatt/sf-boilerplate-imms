<?php

namespace AppBundle\Event;

use AppBundle\Entity\Solicitud;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class SolicitudEvent
 * @package AppBundle\Event
 */
class SolicitudEvent extends Event
{
  const SOLICITUD_CREADA   = 'solicitud.creada';
  const SOLICITUD_TERMINADA = 'solicitud.terminada';
  const MONTOS_REGISTRADOS = 'solicitud.montos_registrados';
  const MONTOS_VALIDADOS = 'solicitud.montos_validados';
  const MONTOS_INCORRECTOS = 'solicitud.montos_incorrectos';
  const FORMATOS_GENERADOS = 'solicitud.formatos_generados';
  const COMPROBANTE_CARGADO = 'solicitud.comprobante_cargado';
  const COMPROBANTE_VALIDADO = 'solicitud.comprobante_validado';


  private $solicitud;

  public function __construct(Solicitud $solicitud)
  {
    $this->solicitud = $solicitud;
  }

  /**
   * @return Solicitud
   */
  public function getSolicitud()
  {
    return $this->solicitud;
  }

}