import * as React from 'react'
import ReactPaginate from "react-paginate";
import ContenedorFiltro from "../../pregrado/components/ContenedorFiltro";
import Buscador from "../../pregrado/components/Buscador";
import OpcionesPageSize from "../../pregrado/components/OpcionesPageSize";
import {getReportePagos, getReportePagosCSV} from "./reportePagos";

const ReporteOportunidad = () => {

  const {useState, useEffect} = React
  const [reportePagos, setReportePagos] = useState([])
  const [desdeSel, setDesde] = useState(null)
  const [hastaSel, setHasta] = useState(null)

  const [search, setSearch] = useState('')
  const [isLoading, toggleLoading] = useState(false)
  const [totalItems, setTotalItems] = useState(0)
  const [totalPages, setTotalPages] = useState(0)
  const [currentPage, setCurrentPage] = useState(1)
  const [pageSize, setPageSize] = useState(10)

  useEffect(()=>{
      getDatosReporte();
    }, [])

  function getDatosReporte(pag=1, limit=pageSize) {
    pag = Number.isInteger(pag) ? pag : 1;
    toggleLoading(true);
    getReportePagos( desdeSel, hastaSel, search, pag, limit)
      .then( (res) => {
          setReportePagos(res.reporte)
          setTotalItems( res.totalItems )
          setTotalPages( res.numPags)
          setCurrentPage(pag)
      }
      ).finally(() => {
      toggleLoading(false)
    })
  }

  function exportar() {
    getReportePagosCSV(desdeSel, hastaSel, search);
  }

  let offset = totalItems > 0 ?
    (pageSize*(currentPage-1)) + 1
    : 0;

  let indexRow = 0;

  function handlePageClick(e) {
    getDatosReporte( e.selected + 1 )
  }

  function handleSearch() {
    getDatosReporte();
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
            <th>Referencia de Pago</th>
            <th>Fecha de depósito</th>
            <th>Fecha de <Facturaci></Facturaci>ón</th>
          </tr>
          </thead>
          <tbody>
          {
            isLoading ?
              <tr>
                <th className='text-center' colSpan={14}>Cargando información...</th>
              </tr>
              :  reportePagos.length > 0 ?
            reportePagos.map( (pago) => (
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
            )) :
              <tr>
                <td colSpan={14} className="text-center"> No hay registros disponibles </td>
              </tr>
          }
          </tbody>
        </table>
        { !isLoading && reportePagos.length > 0 ?
          <div className="col-md-12">
            <div className="col-md-3">
              {offset} - {offset + (reportePagos.length - 1)} de {totalItems}
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

export default ReporteOportunidad
