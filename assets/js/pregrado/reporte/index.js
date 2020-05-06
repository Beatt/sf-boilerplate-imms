import * as React from 'react'
import ReactDOM from 'react-dom'
//import ReactPaginate from 'react-paginate';
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
          onChange={({target}) => handler(target) }
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
    /* setCarreraSel(e); */
    console.log("Componente Filtro, valor recibido: " + e);
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

  console.log("Tabla Campos");
  console.log(props.isLoading);
  console.log(props.camposClinicos);

  return (
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
        props.camposClinicos.map((campoClinico, index) => (
          <tr key={index}>
            <th><a href="">{  campoClinico.convenio.delegacion ? campoClinico.convenio.delegacion.nombre : ""}</a></th>
            <th>{campoClinico.unidad ? campoClinico.unidad.nombre : ""}</th>
            <th>{campoClinico.convenio.institucion.nombre}</th>
            <th>{campoClinico.solicitud.noSolicitud}</th>
            <th>{ campoClinico.convenio.cicloAcademico ? campoClinico.convenio.cicloAcademico.nombre : "" }</th>
            <th>{campoClinico.convenio.carrera.nivelAcademico.nombre}
              - {campoClinico.convenio.carrera.nombre}</th>
            <th> </th>
            <th>{campoClinico.lugaresSolicitados}</th>
            <th>{campoClinico.lugaresAutorizados}</th>
            <th>{(new Date(campoClinico.fechaInicial)).toLocaleDateString()}
            - {new Date(campoClinico.fechaFinal).toLocaleDateString()} </th>
            <th>{campoClinico.estatus.nombre}</th>
          </tr>
        ))
      }
      </tbody>
    </table>
  );
};

const Index = () => {
  const { useState, useEffect } = React

  const [search, setSearch] = useState('')
  const [ currentPage, setCurrentPage ] = useState(1)
  const [isLoading, toggleLoading] = useState(false)

  const [camposClinicos, setCamposClinicos] = useState([])
  const [carreraSel, setCarreraSel] = useState(null)
  const [cicloAcademicoSel, setCASel] = useState(null)
  const [delegacionSel, setDelegacionSel] = useState(null)
  const [estadoSolSel, setEstadoSolSel] = useState(null)

  useEffect(()=>{
    getCampos();
  }, []);

  function handleSearch() {
    console.log("ejecutando handleSearch...");
    if (!search && !carreraSel && !cicloAcademicoSel
      && !delegacionSel && !estadoSolSel) return;
    getCampos()
  }

  function getCampos() {
    console.log("ejecutando getCampos...");
    toggleLoading(true)
    getCamposClinicos(
      cicloAcademicoSel, delegacionSel, carreraSel,
      estadoSolSel, search, currentPage
    ).then((res) => {
      setCamposClinicos(res.camposClinicos)
    })
      .finally(() => {
        console.log("finally get campos...");
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
      />
    </React.Fragment>
  );
}

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Index  />,
    document.getElementById('reporte-wrapper')
  )
})
