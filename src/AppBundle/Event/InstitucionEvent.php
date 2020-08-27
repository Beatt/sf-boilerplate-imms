<?php

namespace AppBundle\Event;

use AppBundle\Entity\Institucion;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class SolicitudEvent
 * @package AppBundle\Event
 */
class InstitucionEvent extends Event
{

  const DATOS_ACTUALIZADOS = 'institucion.datos_actualizados';
  const CIF_ACTUALIZADO = 'institucion.cif_actualizado';

  private $institucion;

  public function __construct(Institucion $institucion)
  {
    $this->institucion = $institucion;
  }

  /**
   * @return Institucion
   */
  public function getInstitucion()
  {
    return $this->institucion;
  }
}