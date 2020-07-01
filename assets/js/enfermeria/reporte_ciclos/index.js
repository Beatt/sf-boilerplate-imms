import * as React from 'react'
import ReactDOM from 'react-dom'
import ReactPaginate from "react-paginate";
import ContenedorFiltro from "../../pregrado/components/ContenedorFiltro";
import Buscador from "../../pregrado/components/Buscador";
import OpcionesPageSize from "../../pregrado/components/OpcionesPageSize";
//import {getReportePagos, getReportePagosCSV} from "./reportePagos";

const Index = (props) => {

  const {useState, useEffect} = React
  const [reporteCiclos, setReporteCiclos] = useState([])
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
    //toggleLoading(true);
    /*getReportePagos( desdeSel, hastaSel, search, pag, limit)
      .then( (res) => {
          console.log(res);
          setReportePagos(res.reporte)
          setTotalItems( res.totalItems )
          setTotalPages( res.numPags)
          setCurrentPage(pag)
        }
      ).finally(() => {
      toggleLoading(false)
    })*/
  }

  function exportar() {
    //getReportePagosCSV(desdeSel, hastaSel, search);
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
            <th>Carrera</th>
            <th>Asignatura</th>
            <th>Institución Educativa</th>
            <th>Inicio</th>
            <th>Término</th>
            <th>Turno</th>
            <th>Alumnos</th>
          </tr>
          </thead>
          <tbody>
          {
            isLoading ?
              <tr>
                <th className='text-center' colSpan={14}>Cargando información...</th>
              </tr>
              : //   reporteCiclos.length > 0 ?
              //reporteCiclos.map( ( campo ) => (
                  <tr key={++indexRow}>
                    <td> Licenciatura en Enfermería </td>
                    <td> (6° Semestre) Atención de Enf. al paciente en Edo. Crítico </td>
                    <td> FAEN </td>
                    <td> 01/02/2019 </td>
                    <td> 30/11/2019 </td>
                    <td> 10-11am </td>
                    <td> 120 </td>
                  </tr>
                //))
             /* :
              <tr>
                <td colSpan={10} className="text-center"> No hay registros disponibles </td>
              </tr> */
          }
          </tbody>
        </table>
        { !isLoading ?
          <div className="col-md-12">
            <div className="col-md-3">
              {offset} - {offset + (reporteCiclos.length - 1)} de {totalItems}
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
