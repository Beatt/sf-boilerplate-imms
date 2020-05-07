import {SOLICITUD, TIPO_PAGO} from "./constants";

export const getActionNameByInstitucionEducativa = (status, tipoPago) => {
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
      if(isMultipleTipoPago(tipoPago)) return 'Ver detalle'
      return 'Cargar comprobante de pago'
    case SOLICITUD.EN_VALIDACION_FOFOE:
      if(isMultipleTipoPago(tipoPago)) return 'Ver detalle'
      return 'Pago correcto'
    case SOLICITUD.CREDENCIALES_GENERADAS:
      return 'Descargar credenciales'
    default:
      console.error(`El action name del estatus ${status} no existe.`)
      return 'Estatus no definido'
  }
}

function isMultipleTipoPago(tipoPago) {
  return tipoPago === TIPO_PAGO.MULTIPLE;
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
      console.error(`Is action disabled del estatus ${status} no existe.`)
      return true
  }
}

/*export const getStatusName = (status) => {
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
      console.error(`El status name del estatus ${status} no existe.`)
      return 'Estatus no definido'
  }
}*/
