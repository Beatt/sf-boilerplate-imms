import ReactDOM from "react-dom";
import React from "react";
import MisSolicitudes from "./MisSolicitudes";

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <MisSolicitudes
      totalInit={window.CAMPOS_CLINICOS_TOTAL_PROPS}
      paginatorTotalPerPage={window.PAGINATOR_TOTAL_PER_PAGE_PROPS}
    />,
    document.getElementById('inicio-component')
  )
})
