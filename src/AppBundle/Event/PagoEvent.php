<?php

namespace AppBundle\Event;

use AppBundle\Entity\Solicitud;
use AppBundle\Entity\SolicitudInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PagoEvent
 * @package AppBundle\Event
 */
class PagoEvent extends Event
{
//  const COMPROBANTE_CARGADO   = 'pago.comprobante_cargado';
  const PAGO_VALIDADO = 'pago.comprobante_valido';
  const PAGO_INCORRECTO = 'pago.comprobante_no_valido';
  const PAGO_REGISTRO_FACTURA = 'pago.factura_cargada';


  private $pago;

  public function __construct(Pago $pago)
  {
    $this->solicitud = $pago;
  }

  /**
   * @return Pago
   */
  public function getPago()
  {
    return $this->pago;
  }

}