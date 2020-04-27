import * as React from 'react'
import ReactDOM from 'react-dom'
import ReactPaginate from 'react-paginate';
import { solicitudesGet } from "../../api/solicitud";

const Index = (
  {
    camposClinicosInit,
    totalInit,
    institucionId,
  }) => {

  const { useState, useEffect } = React
  const [ camposClinicos, setCamposClinicos ] = useState(camposClinicosInit)
  const [ search, setSearch ] = useState('')
  const [ total, setTotal ] = useState(totalInit)
  const [ currentPage, setCurrentPage ] = useState('')
  const [ isLoading, toggleLoading ] = useState(false)

  useEffect(() => {
    if(currentPage) getCamposClinicos()
  }, [currentPage])

  const ESTATUS_BUTTON = {
    nueva: {
      name: 'Nueva',
      button: 'Registre montos'
    },
    en_espera_de_validacion_de_montos: {
      name: 'En espera de validación de montos',
      button: 'Corrija montos'
    },
    montos_validados: {
      name: 'Montos validados',
      button: 'Consulte formatos de pago'
    },
    pago_en_proceso: {
      name: 'Pago en proceso',
      button: 'Cargue comprobante de pago'
    },
    pagado: {
      name: 'Pagado',
      button: 'Dercargue factura'
    },
    en_validacion_por_fofoe: {
      name: 'En validación por FOFOE',
      button: 'Corrija montos'
    },
  }

  function handleSearch() {
    if(!search) return;
    getCamposClinicos()
  }

  function getCamposClinicos() {
    toggleLoading(true)

    solicitudesGet(institucionId, currentPage, search)
      .then((res) => {
        setCamposClinicos(res.camposClinicos)
        setTotal(res.total)
      })
      .finally(() => {
        toggleLoading(false)
      })
  }

  return(
    <div className='row'>
      <div className="col-md-7"/>
      <div className='col-md-5 mb-10'>
        <div className='navbar-form navbar-right'>
          <div className="form-group">
            <input
              type="text"
              placeholder='Buscar por...'
              className='input-sm form-control'
              onChange={({ target }) => setSearch(target.value)}
            />
          </div>
          <button
            type="button"
            className="btn btn-default"
            onClick={handleSearch}
          >
            Buscar
          </button>
        </div>
      </div>
      <div className='col-md-12'>
        <table className='table table-bordered'>
          <thead className='headers'>
          <tr>
            <th>No. de solicitud</th>
            <th>No. de campos clínicos <br/>solicitados</th>
            <th>No. de campos clínicos <br/>autorizados</th>
            <th>Fecha de solicitud</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
          </thead>
          <tbody>
          {
            isLoading ?
              <tr>
                <th className='text-center' colSpan={6}>Cargando información...</th>
              </tr> :
              camposClinicos.map((campoClinico, index) => (
                <tr key={index}>
                  <th>{campoClinico.noSolicitud}</th>
                  <th>{campoClinico.noCamposSolicitados}</th>
                  <th>{campoClinico.noCamposAutorizados}</th>
                  <th>{campoClinico.fecha}</th>
                  <th>{ESTATUS_BUTTON[campoClinico.estatus].name}</th>
                  <th>
                    <button className='btn btn-default'>
                      {ESTATUS_BUTTON[campoClinico.estatus].button}
                    </button>
                  </th>
                </tr>
              ))
          }
          </tbody>
        </table>
      </div>
      <div className="col-md-12 text-center">
        <ReactPaginate
          previousLabel={'Anterior'}
          nextLabel={'Siguiente'}
          breakLabel={'...'}
          breakClassName={'break-me'}
          pageCount={total}
          marginPagesDisplayed={5}
          pageRangeDisplayed={2}
          onPageChange={(currentPage) => {
            setCurrentPage(currentPage.selected + 1)
          }}
          containerClassName={'pagination'}
          subContainerClassName={'pages pagination'}
          activeClassName={'active'}
        />
      </div>
    </div>
  )
}

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Index
      camposClinicosInit={window.CAMPOS_CLINICOS_PROPS}
      totalInit={window.CAMPOS_CLINICOS_TOTAL_PROPS}
      institucionId={window.INSTITUCION_ID_PROPS}
    />,
    document.getElementById('solicitud-index-component')
  )
})
