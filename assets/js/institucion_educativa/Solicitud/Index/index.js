import * as React from 'react'
import ReactDOM from 'react-dom'
import ReactPaginate from 'react-paginate';
import { solicitudesGet } from "../../api/solicitud";
import { TIPO_PAGO } from "./constants";

const Index = (
  {
    totalInit,
    institucionId,
  }) => {

  const { useState, useEffect } = React
  const [ camposClinicos, setCamposClinicos ] = useState([])
  const [ search, setSearch ] = useState('')
  const [ tipoPago, setTipoPago ] = useState('unico')
  const [ total, setTotal ] = useState(totalInit)
  const [ currentPage, setCurrentPage ] = useState(1)
  const [ isLoading, toggleLoading ] = useState(false)

  useEffect(() => {
    if(currentPage !== null || tipoPago !== '') getCamposClinicos()
  }, [currentPage, tipoPago])

  function handleSearch() {
    if(!search) return;
    getCamposClinicos()
  }

  function getCamposClinicos() {
    toggleLoading(true)

    solicitudesGet(
      institucionId,
      tipoPago,
      currentPage,
      search
    )
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
      <div className="col-md-3">
        <div className="form-group">
          <label htmlFor="solicitud_tipoPago">Tipo de pago</label>
          <select
            id="solicitud_tipoPago"
            className='form-control'
            onChange={({ target }) => setTipoPago(target.value)}
          >
            <option value={TIPO_PAGO.UNICO}>Pago único</option>
            <option value={TIPO_PAGO.MULTIPLE}>Pago multiple</option>
          </select>
        </div>
      </div>
      <div className="col-md-3"/>
      <div className='col-md-6 mb-10 text-right'>
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

      <div className="col-md-12">
        <div className="panel panel-default">
          <div className="panel-body">
            <table className='table'>
              <thead className='headers'>
              <tr>
                <th>No. de solicitud</th>
                <th>No. de campos clínicos <br/>solicitados</th>
                <th>No. de campos clínicos <br/>autorizados</th>
                <th>Fecha de solicitud</th>
                <th>Tipo de pago</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
              </thead>
              <tbody>
              {
                isLoading ?
                  <tr>
                    <th className='text-center' colSpan={7}>Cargando información...</th>
                  </tr> :
                  camposClinicos.map((campoClinico, index) => (
                    <tr key={index}>
                      <th><a href="">{campoClinico.noSolicitud}</a></th>
                      <th>{campoClinico.noCamposSolicitados}</th>
                      <th>{campoClinico.noCamposAutorizados}</th>
                      <th>{campoClinico.fecha}</th>
                      <th>{campoClinico.tipoPago}</th>
                      <th>{campoClinico.estatusActual}</th>
                      <th>
                        <button className='btn btn-default'>Ver detalle</button>
                      </th>
                    </tr>
                  ))
              }
              </tbody>
            </table>
            <div className="text-center">
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
        </div>
      </div>
    </div>
  )
}

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Index
      totalInit={window.CAMPOS_CLINICOS_TOTAL_PROPS}
      institucionId={window.INSTITUCION_ID_PROPS}
    />,
    document.getElementById('solicitud-index-component')
  )
})
