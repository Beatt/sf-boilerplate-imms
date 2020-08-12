<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

class FormatosFofoe extends AbstractDocument implements DocumentInterface
{
    const NAME = "Formatos FOFOE";

    public function __construct($fecha, $descripcion, $urlArchivo)
    {
        parent::__construct(self::NAME, $fecha, $descripcion, $urlArchivo);
    }
}
