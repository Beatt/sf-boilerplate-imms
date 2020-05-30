import * as React from 'react'
import ReactDOM from 'react-dom'
import DetalleCamposClinicos from "./DetalleCamposClinicos";
import ReferenciaPago from "./ReferenciaPago";

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <DetalleCamposClinicos
      camposClinicos={window.CAMPOS_CLINICOS_PROPS}
    />,
    document.getElementById('detalle_campos_clinicos-component')
  )
  ReactDOM.render(
    <ReferenciaPago
    />,
    document.getElementById('referencia_pago-component')
  )
})
