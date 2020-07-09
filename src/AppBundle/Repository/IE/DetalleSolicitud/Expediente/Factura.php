<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

final class Factura extends AbstractDocument implements DocumentInterface
{
    const NAME = "Factura (CFDI)";

    public function __construct($fecha, $descripcion, $urlArchivo)
    {
        parent::__construct(self::NAME, $fecha, $descripcion, $urlArchivo);
    }
}
