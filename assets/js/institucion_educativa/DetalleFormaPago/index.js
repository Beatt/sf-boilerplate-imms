import * as React from 'react'
import ReactDOM from 'react-dom'
import ReferenciaPago from "./ReferenciaPago";

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <ReferenciaPago
      tipoPagoSelected={window.TIPO_PAGO_SELECTED}
      descargarReferenciasBancariasPath={window.DESCARGAR_REFERENCIAS_BANCARIAS_PATH}
    />,
    document.getElementById('detalle-forma-pago-component')
  )
})
