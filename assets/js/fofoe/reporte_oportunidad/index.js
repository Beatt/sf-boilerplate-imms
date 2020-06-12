import * as React from 'react'
import ReactDOM from 'react-dom'

const Index = (props) => {

  const {useState, useEffect} = React
  const [reporteIngresos, setReporteIngresos] = useState(props.pagos)
  const [isLoading, toggleLoading] = useState(false)
  const [anioSel, setAnioSel] = useState(new Date().getFullYear());


  console.log(reporteIngresos.pagos);

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
  let indexRow = 0;

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
            <th rowSpan={2} >Delegación </th>
            <th rowSpan={2}>Campo Clínico</th>
            <th colSpan={2} >Ciclo</th>
            <th rowSpan={2} >Institución </th>
            <th rowSpan={2}>Alumnos</th>
            <th colSpan={4}>Datos de la aportación</th>
            <th rowSpan={2} >Indicador</th>
            <th rowSpan={2} >Dias</th>
          </tr>
          <tr>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Importe</th>
            <th>Folio Host o Referencia de Transferencia</th>
            <th>Fecha de depósito</th>
            <th>Fecha de Facturación</th>
          </tr>
          </thead>
          <tbody>
          {

            props.pagos.map( (pago) => (
              pago.camposPagados.campos.map( ( campo ) => (
                <tr key={++indexRow}>
                  <td> {indexRow} </td>
                  <td> {campo.displayDelegacion} </td>
                  <td> {campo.displayCicloAcademico} </td>
                  <td> {campo.displayCarrera} </td>
                  <td> {campo.fechaInicialFormatted} </td>
                  <td> {campo.fechaFinalFormatted} </td>
                  <td> {pago.solicitud.institucion.nombre} </td>
                  <td> {campo.lugaresAutorizados} </td>
                  <td> {campo.monto} </td>
                  <td> {pago.referenciaBancaria} </td>
                  <td> {pago.fechaPagoFormatted} </td>
                  <td></td>
                  <td> {pago.camposPagados.tiempos[campo.id] >= 14 ? 'CUMPLE' : 'NO CUMPLE'} </td>
                  <td> {pago.camposPagados.tiempos[campo.id]}</td>
                </tr>
              ) )
            ))
          }
          </tbody>
        </table>
      </div>

    </div>
  );
};

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Index pagos={window.PAGOS} />,
    document.getElementById('reporte-wrapper')
  )
})
