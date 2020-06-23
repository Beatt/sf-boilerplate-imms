<?php

namespace AppBundle\Service;

use AppBundle\Entity\Pago;
use AppBundle\Entity\Solicitud;
use AppBundle\Util\StringUtil;

class GeneradorReferenciaBancaria implements GeneradorReferenciaBancariaInterface
{
    public function makeReferenciaBancaria(Pago $pago, $id) {
      if (!$pago) return "00000000";
      $solicitud = $pago->getSolicitud();
      $delegacion = $solicitud->getDelegacion();
      $institucion = $solicitud->getInstitucion();
      $esPagoUnico = $solicitud->getTipoPago() == Solicitud::TIPO_PAGO_UNICO;

      $referencia = "";
      $referencia .= $delegacion->getClaveDelegacional(); // 2 caracteres
      $referencia .= sprintf("%03d", $institucion->getId() % 1000);
      $referencia .= $esPagoUnico ? "1" : "0";
      $referencia .= sprintf("%04d", $id% 10000);

      return $referencia;
    }
}
