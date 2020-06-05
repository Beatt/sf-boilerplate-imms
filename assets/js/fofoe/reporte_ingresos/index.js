import * as React from 'react'
import ReactDOM from 'react-dom'
import {getReporteIngresos} from "./reporteIngresos";

const Index = () => {

  const {useState, useEffect} = React
  const [reporteIngresos, setReporteIngresos] = useState([])
  const [isLoading, toggleLoading] = useState(false)
  const [anioSel, setAnioSel] = useState(new Date().getFullYear());

  useEffect(() => {
    getDatosReporte();
  }, []);

  function getDatosReporte() {
    getReporteIngresos().then((res) => {
      setReporteIngresos(res.reporte)
    })
  }

  function exportar() {
    getReporteIngresos(anioSel, 1);
  }

  let urlExport = `/fofoe/reporte_ingresos?anio=${anioSel}&export=1`
  let totalIngsCCs = 0
  let totalIngsInt = 0
  let totalGrl = 0

  return (
    <div className="panel panel-default">

      <div className="panel-heading">
        Reporte de ingresos por concepto
      </div>
      <div className="panel-body">
        <a href={urlExport} >Descargar CSV</a>

        <table className="table">
          <thead>
          <tr>
            <td>Mes/Año</td>
            <td>CC Área de la Salud</td>
            <td>Int. Méd</td>
            <td>Total Mensual</td>
          </tr>
          </thead>
          <tbody>
          {
            reporteIngresos.map( (ingresos, index) => (
              <tr key={index}>
                <td> {ingresos.Mes} / { ingresos.Anio}</td>
                <td> {ingresos.ingCCS}</td>
                <td> {ingresos.ingINT}</td>
                <td> {ingresos.Total}</td>
              </tr>
            ))
          }
          </tbody>
          <tfoot>
          <tr>
            <td>Total por ciclo</td>
            <td> {totalIngsCCs} </td>
            <td> {totalIngsInt} </td>
            <td> {totalGrl} </td>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>
  );
};

      document.addEventListener('DOMContentLoaded', () => {
      ReactDOM.render(
        <Index/>,
        document.getElementById('reporte-wrapper')
      )
    })
