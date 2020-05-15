import * as React from 'react'
import ReactDOM from 'react-dom'
import ReactPaginate from 'react-paginate';
import {getCarreras, getCiclosAcademicos, getDelegaciones, getEstatusCampoClinico} from "../api/catalogos";
import {getCamposClinicos, getCamposClinicosCSV} from "./campos"
import {Fragment} from "react";

const ContenedorFiltro = ({
                            EtiquetaFiltro, name, valores, setValSel, type
                          }) => {

  function handler(e) {
    setValSel(e.value !== '' ? e.value : null)
  }

  return (
    <div className="col-md-3">
      <div className="form-group">
        <label htmlFor={name}>{EtiquetaFiltro}</label>
        {type === "Select" ?
          <Fragment>
            <select
              name={name}
              id={"id" + name}
              className='form-control'
              onChange={({target}) => handler(target)}
            >
              <option value="">Elige una opción</option>
              { valores.map((valor) =>
                  <option  value={valor.id}  key={valor.id}>
                    {valor.nombre}
                  </option>
              )}
            </select>
          </Fragment>
         : type === "date" ?
            <Fragment>
              <input className='form-control' type='date'
                     name={name}
                     id={"id" + name}
                     onChange={({target}) => handler(target)} />
            </Fragment>
            : ''
        }
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

  function handlerFiltro() {

  }

  return (
    <Fragment>
    <div className="row">
      <ContenedorFiltro
        EtiquetaFiltro="Ciclo Académico"
        name="CicloAcademico"
        valores={tiposCA}
        setValSel={props.setCASel}
        type="Select"
      />
      <ContenedorFiltro
        EtiquetaFiltro="Delegación"
        name="Delegacion"
        valores={delegaciones}
        setValSel={props.setDelegacionSel}
        type="Select"
      />
      <ContenedorFiltro
        EtiquetaFiltro="Carrera"
        name="Carrera"
        valores={carreras}
        setValSel={props.setCarreraSel}
        type="Select"
      />
      <ContenedorFiltro
        EtiquetaFiltro="Estado de la Solicitud"
        name="EstadoSol"
        valores={estadosSol}
        setValSel={props.setEstadoSolSel}
        type="Select"
      />
      <ContenedorFiltro
        EtiquetaFiltro="Fecha incio a partir de:"
        name="FechaInicio"
        valores={[]}
        setValSel={props.setFechaIniSel}
        type="date"
      />
      <ContenedorFiltro
        EtiquetaFiltro="Fecha de fin antes de:"
        name="FechaInicio"
        valores={[]}
        setValSel={props.setFechaFinSel}
        type="date"
      />

    </div>
    <Buscador
      setSearch={props.setSearch}
      handleSearch={props.handleSearch}
      handleExport={props.handleExport}
      setPageSize={props.setPageSize}
      pageSize={props.pageSize}
    />
    </Fragment>
  );
}

const Buscador = (
props
) => {
  return (
    <div className='row'>
      <div className='col-md-3'>
        <button
          type='button'
          className='btn btn-success'
          onClick={props.handleExport}
        >
          Exportar CSV
        </button>
      </div>
      <div className='col-md-9 mb-15'>
      <div className='navbar-form navbar-right '>
        <div className="form-group">
          <input
            type="text"
            placeholder='Buscar por...'
            className='input-sm form-control'
            onChange={({target}) => props.setSearch(target.value)}
          />
        </div>
        <button
          type="button"
          className="btn btn-default"
          onClick={props.handleSearch}
        >
          Buscar
        </button>
      </div>
      </div>
    </div>
  );
}

const OpcionesPageSize = (props) => {

  function handlerPageSize(e) {
    props.setPageSize(parseInt(e.value));
    props.handleSearch(1, e.value);
  }

  return (
      <label> Mostrar
        <select
                onChange={({target}) => handlerPageSize(target)}>
          <option value='5' key='5'>5</option>
          <option value='10'>10</option>
          <option value='30'>30</option>
          <option value='50'>50</option>
        </select>
        registros
      </label>
  );
}

const TablaCampos = (props) => {

  var offset = props.totalItems > 0 ?
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
                 props.totalItems > 0 ?
                props.camposClinicos.map((campoClinico, index) => (
                  <tr key={index}>
                    <td><a href="">{campoClinico.convenio.delegacion ? campoClinico.convenio.delegacion.nombre : ""}</a>
                    </td>
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
                    <td>{campoClinico.estatus.nombre}</td>
                  </tr>
                ))
                  : <tr>
                    <td className='text-center' colSpan={11}>No hay registros disponibles</td>
                  </tr>
            }
            </tbody>
          </table>
          { !props.isLoading ?
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

const Index = () => {
  const {useState, useEffect} = React

  const [search, setSearch] = useState('')
  const [isLoading, toggleLoading] = useState(false)
  const [totalItems, setTotalItems] = useState(0)
  const [totalPages, setTotalPages] = useState(0)
  const [currentPage, setCurrentPage] = useState(1)
  const [pageSize, setPageSize] = useState(5)

  const [camposClinicos, setCamposClinicos] = useState([])
  const [carreraSel, setCarreraSel] = useState(null)
  const [cicloAcademicoSel, setCASel] = useState(null)
  const [delegacionSel, setDelegacionSel] = useState(null)
  const [estadoSolSel, setEstadoSolSel] = useState(null)
  const [fechaIniSel, setFechaIniSel] = useState(null)
  const [fechaFinSel, setFechaFinSel] = useState(null)
  //const offset = 0

  useEffect(() => {
    getCampos();
  }, []);

  function handleSearch(pag=1, limit=pageSize) {
    /* if (!carreraSel && !cicloAcademicoSel
      && !delegacionSel && !estadoSolSel
    && !fechaIniSel && !fechaFinSel) return; */
    getCampos(pag, limit)
  }

  function handleExport() {
    getCamposClinicosCSV(
      cicloAcademicoSel, delegacionSel, carreraSel,
      estadoSolSel, fechaIniSel, fechaFinSel, search);
  }

  function getCampos(pag=1, limit=pageSize) {
    toggleLoading(true);
    console.log(limit)
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
    <React.Fragment>
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
        setCurrentPage={setCurrentPage}
        getCampos={getCampos}
        handleSearch={handleSearch}
      />
    </React.Fragment>
  );
}

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Index />,
    document.getElementById('reporte-wrapper')
  )
})
