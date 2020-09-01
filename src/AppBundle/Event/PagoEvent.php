<?php

namespace AppBundle\Event;

use AppBundle\Entity\Pago;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PagoEvent
 * @package AppBundle\Event
 */
class PagoEvent extends Event
{
  const PAGO_VALIDADO = 'pago.comprobante_valido';
  const PAGO_INCORRECTO = 'pago.comprobante_no_valido';


  private $pago;

  public function __construct(Pago $pago)
  {
    $this->pago = $pago;
  }

  /**
   * @return Pago
   */
  public function getPago()
  {
    return $this->pago;
  }

}