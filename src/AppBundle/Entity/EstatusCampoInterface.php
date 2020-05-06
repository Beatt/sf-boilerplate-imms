<?php

namespace AppBundle\Entity;

interface EstatusCampoInterface
{
    CONST NUEVO = 'nuevo';
    CONST PENDIENTE_DE_PAGO = 'pendiente_de_pago';
    CONST PAGO = 'pago';
    CONST PAGO_VALIDADO_FOFOE = 'pago_validado_fofoe';
    CONST PAGO_NO_VALIDADO = 'pago_no_validado';
    CONST PENDIENTE_FACTURA_FOFOE = 'pendiente_factura_fofoe';
    const CREDENCIALES_GENERADAS = 'credenciales_generadas';
}
