import * as React from 'react'
import ReactDOM from 'react-dom'
import ReactPaginate from "react-paginate";
import ContenedorFiltro from "../../pregrado/components/ContenedorFiltro";
import Buscador from "../../pregrado/components/Buscador";
import OpcionesPageSize from "../../pregrado/components/OpcionesPageSize";
import {getReportePagos, getReportePagosCSV} from "./reportePagos";

const Index = (props) => {

  const {useState, useEffect} = React
  const [reportePagos, setReportePagos] = useState(props.pagos)
  const [desdeSel, setDesde] = useState(null)
  const [hastaSel, setHasta] = useState(null)

  const [search, setSearch] = useState('')
  const [isLoading, toggleLoading] = useState(false)
  const [totalItems, setTotalItems] = useState(0)
  const [totalPages, setTotalPages] = useState(0)
  const [currentPage, setCurrentPage] = useState(1)
  const [pageSize, setPageSize] = useState(10)

  let offset = totalItems > 0 ?
    (pageSize*(currentPage-1)) + 1
    : 0;

  function getDatosReporte(pag=1, limit=pageSize, exportar=false) {
    /*getReporteIngresos().then((res) => {
      setReporteIngresos(res.reporte)
    })*/
  }

  function handleSearch(pag=1, limit=pageSize) {
    getCampos(pag, limit)
  }

  function exportar() {
    getReportePagosCSV(desdeSel, hastaSel, search);
  }
  
  function handleDesde(e) {
    setDesde(e.value);
  }

  function handleHasta(e) {
    setHasta(e.value)
  }

  let urlExport = `/fofoe/reporte_oportunidad_pago?export=1`
  let totalIngsCCs = 0
  let totalIngsInt = 0
  let totalGrl = 0
  let indexRow = 0;

  function handlePageClick(e) {

  }

  return (
    <div className="panel panel-default">

      <div className="panel-heading">
        Pago oportuno de cuotas de recuperación al Fondo de Fomento a la Educación (FOFOE)
      </div>
      <div className="panel-body">

        <div className="row">
          <div className="col-md-2">Período de Consulta de pagos recibidos</div>
          <ContenedorFiltro
            EtiquetaFiltro={"Desde: "}
            setValSel={setDesde}
            valores={[]}
            name="desde"
            tipo="date"
          />
          <ContenedorFiltro
            EtiquetaFiltro={"Hasta: "}
            setValSel={setHasta}
            valores={[]}
            name="desde"
            tipo="date"
          />
        </div>
        <Buscador
          handleSearch={ handleSearch }
          handleExport={ exportar }
          setSearch={setSearch}
        />

        <OpcionesPageSize
          setPageSize={setPageSize}
          handleSearch={handleSearch}
        />
        <table className="table">
          <thead>
          <tr>
            <th rowSpan={2} >Consecutivo</th>
            <th rowSpan={2} >Delegación </th>
            <th rowSpan={2}>Campo Clínico</th>
            <th rowSpan={2}>Carrera</th>
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
        { !isLoading ?
          <div className="col-md-12">
            <div className="col-md-3">
              {offset} - {offset } de {totalItems}
            </div>
            <div className="col-md-9 text-center">
              <ReactPaginate
                pageCount={totalPages}
                marginPagesDisplayed={5}
                pageRangeDisplayed={3}
                previousLabel={'Anterior'}
                nextLabel={'Siguiente'}
                breakLabel={'...'}
                breakClassName={'break-me'}
                onPageChange={(e) => { handlePageClick(e) }}
                containerClassName={'pagination'}
                subContainerClassName={'pages pagination'}
                activeClassName={'active'}
                forcePage={currentPage - 1}
              />
            </div>
          </div>
          : ''
        }
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
