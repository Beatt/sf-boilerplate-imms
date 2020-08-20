import * as React from 'react'
import {getCamposClinicos, getCamposClinicosCSV} from "../reporte/campos";
import {getCarreras, getCiclosAcademicos, getDelegaciones, getEstatusCampoClinico} from "../api/catalogos";
import ContenedorFiltro from "./ContenedorFiltro";
import Buscador from "./Buscador";
import OpcionesPageSize from "./OpcionesPageSize";
import ReactPaginate from "react-paginate";

const ReporteDetalle = () => {
  const {useState, useEffect} = React

  const [search, setSearch] = useState('')
  const [isLoading, toggleLoading] = useState(false)
  const [totalItems, setTotalItems] = useState(0)
  const [totalPages, setTotalPages] = useState(0)
  const [currentPage, setCurrentPage] = useState(1)
  const [pageSize, setPageSize] = useState(10)

  const [camposClinicos, setCamposClinicos] = useState([])
  const [carreraSel, setCarreraSel] = useState(null)
  const [cicloAcademicoSel, setCASel] = useState(null)
  const [delegacionSel, setDelegacionSel] = useState(null)
  const [estadoSolSel, setEstadoSolSel] = useState(null)
  const [fechaIniSel, setFechaIniSel] = useState(null)
  const [fechaFinSel, setFechaFinSel] = useState(null)

  useEffect(() => {
    getCampos();
  }, []);

  function handleSearch(pag=1, limit=pageSize) {
    getCampos(pag, limit)
  }

  function handleExport() {
    getCamposClinicosCSV(
      cicloAcademicoSel, delegacionSel, carreraSel,
      estadoSolSel, fechaIniSel, fechaFinSel, search);
  }

  function getCampos(pag=1, limit=pageSize) {
    pag = Number.isInteger(pag) ? pag : 1;
    toggleLoading(true);
    getCamposClinicos(
      cicloAcademicoSel, delegacionSel, carreraSel,
      estadoSolSel, fechaIniSel, fechaFinSel,
      search, pag, limit
    ).then((res) => {
      setCamposClinicos(res.camposClinicos)
      setTotalItems( res.totalItems )
      setTotalPages( res.numPags)
      setCurrentPage(pag)
    })
      .finally(() => {
        toggleLoading(false)
      })
  }

  return (
    <div className="panel panel-default">
      <div className="panel-heading">
        Reporte detallado de campos clínicos - Ciclos Clínicos e Internado Médico
      </div>
      <div className="panel-body">
        <Filtros
          setCarreraSel={setCarreraSel}
          setCASel={setCASel}
          setDelegacionSel={setDelegacionSel}
          setEstadoSolSel={setEstadoSolSel}
          setFechaIniSel={setFechaIniSel}
          setFechaFinSel={setFechaFinSel}

          setSearch={setSearch}
          handleSearch={handleSearch}
          handleExport={handleExport}
          setPageSize={setPageSize}
          pageSize={pageSize}
        />
        <TablaCampos
          isLoading={isLoading}
          camposClinicos={camposClinicos}
          pageSize={pageSize}
          setPageSize={setPageSize}
          totalPages={totalPages}
          totalItems={totalItems}
          currentPage={currentPage}
          getCampos={getCampos}
          handleSearch={handleSearch}
        />
      </div>
    </div>
  );
}

const Filtros = (
  props
) => {

  const {useEffect, useState} = React
  const [carreras, setCarreras] = useState([])
  const [tiposCA, setTipos] = useState([])
  const [delegaciones, setDelegaciones] = useState([])
  const [estadosSol, setEstadosSol] = useState([])

  useEffect(() => { // catalogos
    getCarreras()
      .then((res) => setCarreras(res.data))
    getDelegaciones()
      .then((res) => setDelegaciones(res.data))
    getCiclosAcademicos()
      .then((res) => setTipos(res.data))
    getEstatusCampoClinico()
      .then((res) => setEstadosSol(res))
  }, []);

  return (
    <React.Fragment>
      <div className="row">
        <ContenedorFiltro
          EtiquetaFiltro="Ciclo Académico"
          name="CicloAcademico"
          valores={tiposCA}
          setValSel={props.setCASel}
          tipo="Select"
        />
        <ContenedorFiltro
          EtiquetaFiltro="Delegación"
          name="Delegacion"
          valores={delegaciones}
          setValSel={props.setDelegacionSel}
          tipo="Select"
        />
        <ContenedorFiltro
          EtiquetaFiltro="Carrera"
          name="Carrera"
          valores={carreras}
          setValSel={props.setCarreraSel}
          tipo="Select"
        />
        <ContenedorFiltro
          EtiquetaFiltro="Estado de la Solicitud"
          name="EstadoSol"
          valores={estadosSol}
          setValSel={props.setEstadoSolSel}
          tipo="Select"
        />
        <ContenedorFiltro
          EtiquetaFiltro="Fecha incio a partir de:"
          name="FechaInicio"
          valores={[]}
          setValSel={props.setFechaIniSel}
          tipo="date"
        />
        <ContenedorFiltro
          EtiquetaFiltro="Fecha de fin antes de:"
          name="FechaInicio"
          valores={[]}
          setValSel={props.setFechaFinSel}
          tipo="date"
        />
      </div>
      <Buscador
        setSearch={props.setSearch}
        handleSearch={props.handleSearch}
        handleExport={props.handleExport}
        setPageSize={props.setPageSize}
        pageSize={props.pageSize}
      />
    </React.Fragment>
  );
}

const TablaCampos = (props) => {

  let offset = props.totalItems > 0 ?
    (props.pageSize*(props.currentPage-1)) + 1
    : 0;

  function handlePageClick(e) {
    props.getCampos( e.selected + 1);
  }

  return (
    <div className="col-md-12">
      <div className="panel panel-default">
        <div className="panel-body">
          <OpcionesPageSize
            setPageSize={props.setPageSize}
            handleSearch={props.handleSearch}
          />
          <table className="table">
            <thead>
            <tr>
              <td>Delegación</td>
              <td>Unidad</td>
              <td>Institución Educativa</td>
              <td>Número de Solicitud</td>
              <td>Ciclo Académico</td>
              <td>Carrera</td>
              <td>Asignatura</td>
              <td>Núm. lugares solicitados</td>
              <td>Núm. lugares autorizados</td>
              <td>Período</td>
              <td>Estado de la solicitud</td>
            </tr>
            </thead>
            <tbody>
            {
              props.isLoading ?
                <tr>
                  <th className='text-center' colSpan={11}>Cargando información...</th>
                </tr> :
                props.camposClinicos.length > 0 ?
                  props.camposClinicos.map((campoClinico, index) => (
                    <tr key={index}>
                      <td>{campoClinico.convenio.delegacion ? campoClinico.convenio.delegacion.nombre : ""}</td>
                      <td>{campoClinico.unidad ? campoClinico.unidad.nombre : ""}</td>
                      <td>{campoClinico.convenio.institucion.nombre}</td>
                      <td>{campoClinico.solicitud.noSolicitud}</td>
                      <td>{campoClinico.convenio.cicloAcademico ? campoClinico.convenio.cicloAcademico.nombre : ""}</td>
                      <td>{campoClinico.convenio.carrera
                        ?  campoClinico.convenio.carrera.displayName
                        : ''}
                      </td>
                      <td>{campoClinico.asignatura} </td>
                      <td>{campoClinico.lugaresSolicitados}</td>
                      <td>{campoClinico.lugaresAutorizados}</td>
                      <td>{ campoClinico.displayFechaInicial}
                        - { campoClinico.displayFechaFinal } </td>
                      <td>{campoClinico.estatus ? campoClinico.estatus.nombre : '?'}</td>
                    </tr>
                  ))
                  : <tr>
                    <td className='text-center' colSpan={11}>No hay registros disponibles</td>
                  </tr>
            }
            </tbody>
          </table>
          { !props.isLoading && props.camposClinicos.length > 0 ?
            <div className="col-md-12">
              <div className="col-md-3">
                {offset} - {offset +
              (props.camposClinicos.length - 1)} de {props.totalItems}
              </div>
              <div className="col-md-9 text-center">
                <ReactPaginate
                  pageCount={props.totalPages}
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
                  forcePage={props.currentPage - 1}
                />
              </div>
            </div>
            : ''
          }
        </div>
      </div>
    </div>
  );
};

export default ReporteDetalle