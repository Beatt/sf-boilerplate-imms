import * as React from 'react'
import ReactDOM from 'react-dom'
import ReactPaginate from 'react-paginate';
import {getCarreras, getCiclosAcademicos, getDelegaciones, getEstatusCampoClinico} from "../api/catalogos";
import {getCamposClinicos} from "./campos"

const ContenedorFiltro = ({
                            EtiquetaFiltro, idFiltro, valores, setValSel
                          }) => {

  function handler(e) {
    setValSel(e.value !== '' ? e.value : null)
  }

  return (
    <div className="col-md-3">
      <div className="form-group">
        <label htmlFor="status">{EtiquetaFiltro}</label>
        <select
          name=""
          id={idFiltro}
          className='form-control'
          onChange={({target}) => handler(target)}
        >
          <option value="">Elige una opción</option>
          {
            valores.map((valor) =>
              <option
                value={valor.id}
                key={valor.id}
              >
                {valor.nombre}
              </option>
            )
          }
        </select>
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

  function updCarreraSel(e) {
    props.setCarreraSel(e);
  }

  return (
    <div className="row">
      <ContenedorFiltro
        EtiquetaFiltro="Ciclo Académico"
        idFiltro="idCicloAcademico"
        valores={tiposCA}
        setValSel={props.setCASel}
      />
      <ContenedorFiltro
        EtiquetaFiltro="Delegación"
        idFiltro="idDelegacion"
        valores={delegaciones}
        setValSel={props.setDelegacionSel}
      />
      <ContenedorFiltro
        EtiquetaFiltro="Carrera"
        idFiltro="idCarrera"
        valores={carreras}
        setValSel={props.setCarreraSel}
      />
      <ContenedorFiltro
        EtiquetaFiltro="Estado de la Solicitud"
        idFiltro="idEstadoSol"
        valores={estadosSol}
        setValSel={props.setEstadoSolSel}
      />

    </div>
  );
}

const Buscador = (
  props
) => {
  return (
    <div className='col-md-12 mb-10'>
      <div className='navbar-form navbar-right'>
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
  );
}

const TablaCampos = (props) => {

  return (
    <div className="col-md-12">
      <div className="panel panel-default">
        <div className="panel-body">
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
                 props.total > 0 ?
                props.camposClinicos.map((campoClinico, index) => (
                  <tr key={index}>
                    <td><a href="">{campoClinico.convenio.delegacion ? campoClinico.convenio.delegacion.nombre : ""}</a>
                    </td>
                    <td>{campoClinico.unidad ? campoClinico.unidad.nombre : ""}</td>
                    <td>{campoClinico.convenio.institucion.nombre}</td>
                    <td>{campoClinico.solicitud.noSolicitud}</td>
                    <td>{campoClinico.convenio.cicloAcademico ? campoClinico.convenio.cicloAcademico.nombre : ""}</td>
                    <td>{campoClinico.convenio.carrera.nivelAcademico.nombre}
                      - {campoClinico.convenio.carrera.nombre}</td>
                    <td> </td>
                    <td>{campoClinico.lugaresSolicitados}</td>
                    <td>{campoClinico.lugaresAutorizados}</td>
                    <td>{(new Date(campoClinico.fechaInicial)).toLocaleDateString()}
                      - {new Date(campoClinico.fechaFinal).toLocaleDateString()} </td>
                    <td>{campoClinico.estatus.nombre}</td>
                  </tr>
                ))
                  : <tr>
                    <td className='text-center' colSpan={11}>No hay registros disponibles</td>
                  </tr>
            }
            </tbody>
          </table>
          <div className="text-center">
            {
                <ReactPaginate
                pageCount={props.total}
                marginPagesDisplayed={5}
                pageRangeDisplayed={3}
                previousLabel={'Anterior'}
                nextLabel={'Siguiente'}
                breakLabel={'...'}
                breakClassName={'break-me'}
                onPageChange={(e) => { props.handlePageClick(e) }}
                containerClassName={'pagination'}
                subContainerClassName={'pages pagination'}
                activeClassName={'active'}
                />
            }
          </div>
        </div>
      </div>
    </div>
  );
};

const Index = () => {
  const {useState, useEffect} = React

  const [search, setSearch] = useState('')
  //const [currentPage, setCurrentPage] = useState(0)
  const [isLoading, toggleLoading] = useState(false)
  const [total, setTotal] = useState(0)

  const [camposClinicos, setCamposClinicos] = useState([])
  const [carreraSel, setCarreraSel] = useState(null)
  const [cicloAcademicoSel, setCASel] = useState(null)
  const [delegacionSel, setDelegacionSel] = useState(null)
  const [estadoSolSel, setEstadoSolSel] = useState(null)

  const perPage = 5;

  useEffect(() => {
    getCampos(1);
  }, []);

  function handleSearch() {
    if (!search && !carreraSel && !cicloAcademicoSel
      && !delegacionSel && !estadoSolSel) return;
    getCampos()
  }

  function handlePageClick(e) {
    //setCurrentPage(  e.selected );
    getCampos( e.selected + 1);
  }

  function getCampos(pag=1) {
    toggleLoading(true)

    getCamposClinicos(
      cicloAcademicoSel, delegacionSel, carreraSel,
      estadoSolSel, search, pag, perPage
    ).then((res) => {
      setCamposClinicos(res.camposClinicos)
      setTotal ( res.numPags )
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
      />
      <Buscador
        setSearch={setSearch}
        handleSearch={handleSearch}
      />
      <TablaCampos
        isLoading={isLoading} camposClinicos={camposClinicos}
        total={total} handlePageClick={handlePageClick}
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
