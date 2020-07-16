import * as React from 'react'
import ReactDOM, { render } from "react-dom";
import ReporteIngresos from '../reporte_ingresos/index'
import ReporteOportunidad from '../reporte_oportunidad/index'
import ReporteDetalle from '../../pregrado/reporte/index'
import ReporteUnidad from '../../enfermeria/reporte_ciclos/index'

const ReportesInicio = () => {

  const {useState, useEffect} = React
  const [reporteActivo, setReporteActivo] = useState('')
  const [isLoading, toggleLoading] = useState(false)

  function cambiaReporte(tipoReporte) {
    toggleLoading(true);
    setReporteActivo(tipoReporte);
    toggleLoading(false);
  }

  return (
    <div>
      <div><h1>Reportes</h1></div>
      <div>
        <ul className="nav nav-pills nav-fill">
          <li className="nav-item">
            <a className="nav-link" href="#" onClick={()=>cambiaReporte('ingresos')} >Ingresos</a>
          </li>
          <li className="nav-item">
            <a className="nav-link" href="#" onClick={()=>cambiaReporte('oportunidad')}>Oportunidad de Pago</a>
          </li>
          <li className="nav-item">
            <a className="nav-link" href="#" onClick={()=>cambiaReporte('detalle')} >Detalle CCS/INT</a>
          </li>
          <li className="nav-item">
            <a className="nav-link" href="#" onClick={()=>cambiaReporte('unidad')}>CCS/INT por Unidad</a>
          </li>
        </ul>
      </div>
      <div className="tab-content" id="myTabContent">
        { (reporteActivo == 'ingresos') ?
              <ReporteIngresos />
           : (reporteActivo == 'oportunidad') ?
              <ReporteOportunidad />
           : (reporteActivo == 'detalle') ?
              <ReporteDetalle />
           : (reporteActivo == 'unidad') ?
              <ReporteUnidad />
           : <div><span> Seleccione un reporte </span></div>
        }
      </div>
    </div>
  );
}

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <ReportesInicio />,
    document.getElementById('reportes-container')
  )
})