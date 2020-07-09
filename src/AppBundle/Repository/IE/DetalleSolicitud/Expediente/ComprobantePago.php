<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

final class ComprobantePago extends AbstractDocument implements DocumentInterface
{
    const NAME = "Comprobante de pago";

    public function __construct($fecha, $descripcion, $urlArchivo)
    {
        parent::__construct(self::NAME, $fecha, $descripcion, $urlArchivo);
    }
}
