<?php

namespace AppBundle\Repository\IE\DetalleSolicitud\Expediente;

final class OficioMontos extends AbstractDocument implements DocumentInterface, OficioMontosFileInterfaces
{
    const NAME = "Oficio de Montos de Colgiatura e inscripción";

    public function __construct($fecha, $descripcion, $urlArchivo)
    {
        parent::__construct(self::NAME, $fecha, $descripcion, $urlArchivo);
    }
}
