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
    toggleLoading(true);
    getReporteIngresos(anioSel).then((res) => {
      console.log(res)
      setReporteIngresos(res.reporte)
    }).finally( () => {
        toggleLoading(false);
        console.log(reporteIngresos);
    })
  }

  function exportar() {
    getReporteIngresos(anioSel, 1);
  }

  function handlerAnioSel(e) {
    setAnioSel(e.value !== '' ? e.value : null);
    getDatosReporte();
  }

  let urlExport = `/fofoe/reporte_ingresos?anio=${anioSel}&export=1`
  let totalIngsVal = 0
  let totalIngsPend = 0
  let anios = ['2020', '2021']

  return (
    <div className="panel panel-default">

      <div className="panel-heading">
        Reporte de ingresos por concepto de Campos Clínicos
        (Ciclos Clínicos e Internado Médico).
      </div>
      <div>
        <div className="col-md-3">
          <div className="">
            <label htmlFor="anio">Año de consulta:</label>
            <select
              name="anio"
              className=''
              onChange={({target}) => handlerAnioSel(target)}
            >
              { anios.map((valor) =>
                <option  value={valor} key={valor} >
                  {valor}
                </option>
              )}
            </select>
          </div>
        </div>
        <div className="col-md-2 col-md-offset-7">
          <a href={urlExport} >Descargar CSV</a>
        </div>
      </div>
      <div className="panel-body">
        <table className="table">
          <thead>
          <tr>
            <th rowSpan={2}>Mes/Año</th>
            <th colSpan={2}>CC Área de la Salud / Int. Méd</th>
          </tr>
          <tr>
            <th>Total Validado</th>
            <th>Total pendiente de validar</th>
          </tr>
          </thead>
          <tbody>
          { isLoading ?
            <tr>
              <td className='text-center' colSpan={3}> Cargando información ... </td>
            </tr>
            : reporteIngresos.length > 0 ?
              reporteIngresos.map( (ingresos, index) => {
                totalIngsPend += parseInt(ingresos.ingPend);
                totalIngsVal += parseInt(ingresos.ingVal);
                  return (
                    <tr key={index}>
                      <td> {ingresos.Mes} / { ingresos.Anio}</td>
                      <td> {ingresos.ingVal}</td>
                      <td> {ingresos.ingPend}</td>
                    </tr>
                  )
              }
                )
            :
            <tr>
              <td className='text-center' colSpan={3}>No hay registros disponibles</td>
            </tr>
          }
          </tbody>
          { isLoading ?
              null
            : reporteIngresos.length > 0 ?
            <tfoot>
            <tr>
            <td>Total</td>
            <td> {totalIngsVal} </td>
            <td> {totalIngsPend} </td>
            </tr>
            </tfoot>
            : null
          }
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
