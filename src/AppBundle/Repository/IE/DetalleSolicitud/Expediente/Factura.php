<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

use AppBundle\Normalizer\FacturaFileInterface;

final class Factura extends AbstractDocument implements DocumentInterface, FacturaFileInterface
{
    const NAME = "Factura (CFDI)";

    public function __construct($fecha, $descripcion, $urlArchivo, $options = null)
    {
        parent::__construct(self::NAME, $fecha, $descripcion, $urlArchivo, $options);
    }
}
