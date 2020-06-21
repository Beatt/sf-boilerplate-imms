import {
  SOLICITUD,
  TIPO_PAGO
} from "./constants";

export const getActionNameByInstitucionEducativa = (estatus, tipoPago) => {
  switch(estatus) {
    case SOLICITUD.CONFIRMADA:
      return 'Registrar montos'
    case SOLICITUD.EN_VALIDACION_DE_MONTOS_CAME:
      return 'En validación por CAME'
    case SOLICITUD.MONTOS_INCORRECTOS_CAME:
      return 'Corregir montos'
    case SOLICITUD.MONTOS_VALIDADOS_CAME:
      return 'Consulte formato de pago'
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
      console.error(`El action name del eetatus ${estatus} no existe.`)
      return 'Estatus no definido'
  }
}

function isMultipleTipoPago(tipoPago) {
  return tipoPago === TIPO_PAGO.MULTIPLE;
}

export const isActionDisabledByInstitucionEducativa = (estatus) => {
  switch(estatus) {
    case SOLICITUD.CONFIRMADA:
      return false
    case SOLICITUD.EN_VALIDACION_DE_MONTOS_CAME:
      return true
    case SOLICITUD.MONTOS_INCORRECTOS_CAME:
      return false
    case SOLICITUD.MONTOS_VALIDADOS_CAME:
      return false
    case SOLICITUD.FORMATOS_DE_PAGO_GENERADOS:
      return false
    case SOLICITUD.CARGANDO_COMPROBANTES:
      return false
    case SOLICITUD.EN_VALIDACION_FOFOE:
      return true
    case SOLICITUD.CREDENCIALES_GENERADAS:
      return false
    default:
      console.error(`Is action disabled del estatus ${estatus} no existe.`)
      return true
  }
}

export const moneyFormat = (monto) => {
  const formatter = new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN',
  });

  return formatter.format(monto)
}
