<?php

namespace AppBundle\Entity;

interface EstatusCampoInterface
{
    const NUEVO = 'Nuevo';
    const PENDIENTE_DE_PAGO = 'Pendiente de pago';
    const PAGO = 'Pago';
    const PAGO_VALIDADO_FOFOE = 'Pago validado FOFOE';
    const PAGO_NO_VALIDO = 'Pago no válido';
    const PENDIENTE_FACTURA_FOFOE = 'Pendiente factura FOFOE';
    const CREDENCIALES_GENERADAS = 'Credenciales generadas';
}
