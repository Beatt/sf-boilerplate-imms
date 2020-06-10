import * as React from 'react'
import ReactDOM from 'react-dom'

const Index = () => {

  const {useState, useEffect} = React
  const [reporteIngresos, setReporteIngresos] = useState([])
  const [isLoading, toggleLoading] = useState(false)
  const [anioSel, setAnioSel] = useState(new Date().getFullYear());

  useEffect(() => {
    getDatosReporte();
  }, []);

  function getDatosReporte() {
    /*getReporteIngresos().then((res) => {
      setReporteIngresos(res.reporte)
    })*/
  }

  function exportar() {
    //getReporteIngresos(anioSel, 1);
  }

  let urlExport = `/fofoe/reporte_ingresos?anio=${anioSel}&export=1`
  let totalIngsCCs = 0
  let totalIngsInt = 0
  let totalGrl = 0

  return (
    <div className="panel panel-default">

      <div className="panel-heading">
        Pago oportuno de cuotas de recuperación al Fondo de Fomento a la Educación (FOFOE)
      </div>
      <div className="panel-body">
        <a href={urlExport} >Descargar CSV</a>

        <table className="table">
          <thead>
          <tr>
            <th rowSpan={2} >Consecutivo</th>
            <th rowSpan={2} >Delegación / UMAE</th>
            <th rowSpan={2}>Campo Clínico</th>
            <th colSpan={2} >Ciclo</th>
            <th rowSpan={2} >RFC (Alumno / Institución)</th>
            <th rowSpan={2}>Total de alumnos solicitados</th>
            <th rowSpan={2}>Total de alumnos autorizados</th>
            <th colSpan={5}>Datos de la aportación</th>
            <th rowSpan={2} >Indicador</th>
          </tr>
          <tr>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Importe</th>
            <th>Folio Host o Referencia de Transferencia</th>
            <th>Fecha de depósito</th>
            <th>Fecha de Facturación</th>
            <th>Cuenta FOFOE</th>
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
