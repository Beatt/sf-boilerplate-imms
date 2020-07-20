<?php

namespace AppBundle\Repository\Fofoe\ValidacionDePago;

use AppBundle\ObjectValues\PagoId;

interface DetallePago
{
    public function detalleByPago(PagoId $pagoId);
}
