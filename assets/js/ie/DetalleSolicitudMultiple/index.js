import * as React from 'react'
import ReactDOM from 'react-dom'
import './styles.scss'
import DetalleSolicitudMultiple from "./Detalle";
import Expediente from "./Expediente";

document.addEventListener('DOMContentLoaded',  () => {
  ReactDOM.render(
    <DetalleSolicitudMultiple
      initCamposClinicos={window.CAMPOS_CLINICOS_PROPS}
    />,
    document.getElementById('detalle-solicitud-multiple-component')
  )
  ReactDOM.render(
    <Expediente
      expediente={window.EXPEDIENTE_PROP}
    />,
    document.getElementById('expediente-solicitud-multiple-component')
  )
})
