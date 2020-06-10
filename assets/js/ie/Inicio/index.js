import ReactDOM from "react-dom";
import React from "react";
import MisSolicitudes from "./MisSolicitudes";

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <MisSolicitudes
      totalInit={window.CAMPOS_CLINICOS_TOTAL_PROPS}
      institucionId={window.INSTITUCION_ID_PROPS}
    />,
    document.getElementById('inicio-component')
  )
})
