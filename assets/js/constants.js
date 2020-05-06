export const SOLICITUD = {}
SOLICITUD.CREADA = 'solicitud_creada';
SOLICITUD.CONFIRMADA = 'solicitud_confirmada';
SOLICITUD.EN_VALIDACION_DE_MONTOS_CAME = 'en_validacion_de_montos_came';
SOLICITUD.MONTOS_INCORRECTOS_CAME = 'montos_incorrectos_came';
SOLICITUD.MONTOS_VALIDADOS_CAME = 'montos_validados_came';
SOLICITUD.FORMATOS_DE_PAGO_GENERADOS = 'formatos_de_pago_generados';
SOLICITUD.CARGANDO_COMPROBANTES = 'formatos_de_pago_generados';
SOLICITUD.EN_VALIDACION_FOFOE = 'en_validacion_fofoe';
SOLICITUD.CREDENCIALES_GENERADAS = 'credenciales_generadas';

export const getActionNameByInstitucionEducativa = (status) => {
  switch(status) {
    case SOLICITUD.CONFIRMADA:
      return 'Registrar montos'
    case SOLICITUD.EN_VALIDACION_DE_MONTOS_CAME:
      return 'En validación por CAME'
    case SOLICITUD.MONTOS_INCORRECTOS_CAME:
      return 'Corregir montos'
    case SOLICITUD.MONTOS_VALIDADOS_CAME:
      return 'Montos validados por CAME'
    case SOLICITUD.FORMATOS_DE_PAGO_GENERADOS:
      return 'Generar formato de pago y referencia'
    case SOLICITUD.CARGANDO_COMPROBANTES:
      return 'Cargar comprobante de pago'
    case SOLICITUD.EN_VALIDACION_FOFOE:
      return 'Pago correcto'
    case SOLICITUD.CREDENCIALES_GENERADAS:
      return 'Descargar credenciales'
    default:
      throw new Error(`El action name del estatus ${status} no existe.`)
  }
}

export const isActionDisabledByInstitucionEducativa = (status) => {
  switch(status) {
    case SOLICITUD.CONFIRMADA:
      return false
    case SOLICITUD.EN_VALIDACION_DE_MONTOS_CAME:
      return true
    case SOLICITUD.MONTOS_INCORRECTOS_CAME:
      return false
    case SOLICITUD.MONTOS_VALIDADOS_CAME:
      return true
    case SOLICITUD.FORMATOS_DE_PAGO_GENERADOS:
      return false
    case SOLICITUD.CARGANDO_COMPROBANTES:
      return false
    case SOLICITUD.EN_VALIDACION_FOFOE:
      return true
    case SOLICITUD.CREDENCIALES_GENERADAS:
      return false
    default:
      throw new Error(`Is action disabled del estatus ${status} no existe.`)
  }
}

export const getStatusName = (status) => {
  switch(status) {
    case SOLICITUD.CREADA:
      return 'Solicitud creada'
    case SOLICITUD.CONFIRMADA:
      return 'Solicitud confirmada'
    case SOLICITUD.EN_VALIDACION_DE_MONTOS_CAME:
      return 'En validación de montos CAME'
    case SOLICITUD.MONTOS_INCORRECTOS_CAME:
      return 'Montos incorrectos CAME'
    case SOLICITUD.MONTOS_VALIDADOS_CAME:
      return 'Montos validados por CAME'
    case SOLICITUD.FORMATOS_DE_PAGO_GENERADOS:
      return 'Formatos de pagos generados'
    case SOLICITUD.CARGANDO_COMPROBANTES:
      return 'Cargando comprobantes'
    case SOLICITUD.EN_VALIDACION_FOFOE:
      return 'En validación FOFOE'
    case SOLICITUD.CREDENCIALES_GENERADAS:
      return 'Credenciales generadas'
    default:
      throw new Error(`El status name del estatus ${status} no existe.`)
  }
}
