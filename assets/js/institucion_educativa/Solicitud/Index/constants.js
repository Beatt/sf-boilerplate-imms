const ESTATUS_TEXTS = {
  solicitud_creada: {
    title: 'Nueva',
    button: 'Registrar montos'
  },
  en_espera_de_validacion_de_montos: {
    title: '',
    button: 'Corrija montos'
  },
  montos_validados: {
    title: 'Montos validados',
    button: 'Consulte formatos de pago'
  },
  pago_en_proceso: {
    title: '',
    button: 'Cargue comprobante de pago'
  },
  pagado: {
    title: '',
    button: 'Dercargue factura'
  },
  en_validacion_por_fofoe: {
    title: '',
    button: 'Corrija montos'
  },
  solicitud_no_autorizada: {
    title: 'ese',
    button: 'ese'
  }
}

const TIPO_PAGO = {
  UNICO: 'unico',
  INDIVIDUAL: 'individual'
}

export {
  ESTATUS_TEXTS,
  TIPO_PAGO
}
