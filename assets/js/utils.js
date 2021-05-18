import {
  SOLICITUD,
  TIPO_PAGO
} from "./constants";

export const getActionNameByInstitucionEducativa = (estatus, tipoPago) => {
  switch(estatus) {
    case SOLICITUD.CONFIRMADA:
      return 'Registrar montos'
    case SOLICITUD.EN_VALIDACION_DE_MONTOS_CAME:
      return 'En validación'
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
      return 'En validación FOFOE'
    case SOLICITUD.CREDENCIALES_GENERADAS: /* Solicitud Pagada */
      return 'Solicitud Pagada'
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
    case SOLICITUD.MONTOS_INCORRECTOS_CAME:
    case SOLICITUD.MONTOS_VALIDADOS_CAME:
    case SOLICITUD.FORMATOS_DE_PAGO_GENERADOS:
    case SOLICITUD.CARGANDO_COMPROBANTES:
      return false
    case SOLICITUD.CREDENCIALES_GENERADAS:
    case SOLICITUD.EN_VALIDACION_DE_MONTOS_CAME:
    case SOLICITUD.EN_VALIDACION_FOFOE:
      return true
    default:
      console.error(`Is action disabled del estatus ${estatus} no existe.`)
      return true
  }
}

export const moneyFormat = (monto) => {
  const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  });

  return formatter.format(monto)
}

export const dateFormat = (date) => {
  const options = {year: 'numeric', month: '2-digit', day: '2-digit'};
  return new Date(date).toLocaleDateString('es-MX', options)
}

export const getSchemeAndHttpHost = () => {
  return window.SCHEMA_AND_HTTP_HOST;
}
