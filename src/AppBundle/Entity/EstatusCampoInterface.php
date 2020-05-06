<?php

namespace AppBundle\Entity;

interface EstatusCampoInterface
{
    CONST NUEVO = 'Nuevo';
    CONST PENDIENTE_DE_PAGO = 'Pendiente de pago';
    CONST PAGO = 'Pago';
    CONST PAGO_VALIDADO_FOFOE = 'Pago validado FOFOE';
    CONST PAGO_NO_VALIDADO = 'Pago no validado';
    CONST PENDIENTE_FACTURA_FOFOE = 'Pendiente factura FOFOE';
    const CREDENCIALES_GENERADAS = 'Credenciales generadas';
}
