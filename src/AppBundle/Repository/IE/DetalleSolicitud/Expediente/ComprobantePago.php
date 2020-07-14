<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

use AppBundle\Normalizer\ComprobantePagoFileInterface;

final class ComprobantePago extends AbstractDocument implements DocumentInterface, ComprobantePagoFileInterface
{
    const NAME = "Comprobante de pago";

    public function __construct($fecha, $descripcion, $urlArchivo)
    {
        parent::__construct(self::NAME, $fecha, $descripcion, $urlArchivo);
    }
}
