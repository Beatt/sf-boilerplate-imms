<?php

namespace AppBundle\Event;

use AppBundle\Entity\Factura;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FacturaEvent
 * @package AppBundle\Event
 */
class FacturaEvent extends Event
{
  const FACTURA_REGISTRADA = 'factura.uploaded';

  /**
   * @var Factura
   */
  private $factura;

  public function __construct(Factura $factura)
  {
    $this->factura = $factura;
  }

  /**
   * @return Factura
   */
  public function getFactura()
  {
    return $this->factura;
  }

}