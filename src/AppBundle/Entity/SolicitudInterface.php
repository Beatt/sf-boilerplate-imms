<?php

namespace AppBundle\Entity;


interface SolicitudInterface
{
    const CREADA = 'solicitud_creada';
    const CONFIRMADA = 'solicitud_confirmada';
    const EN_VALIDACION_DE_MONTOS_CAME = 'en_validacion_de_montos_came';
    const MONTOS_INCORRECTOS_CAME = 'montos_incorrectos_came';
    const MONTOS_VALIDADOS_CAME = 'montos_validados_came';
    const FORMATOS_DE_PAGO_GENERADOS = 'formatos_de_pago_generados';
    const CARGANDO_COMPROBANTES = 'formatos_de_pago_generados';
    const EN_VALIDACION_FOFOE = 'en_validacion_fofoe';
    const CREDENCIALES_GENERADAS = 'credenciales_generadas';
}
