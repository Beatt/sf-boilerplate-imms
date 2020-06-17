import * as React from 'react'
import ReactPaginate from 'react-paginate';
import { solicitudesGet } from "../../api/solicitud";
import { TIPO_PAGO, SOLICITUD } from "../../../constants";
import {
  getActionNameByInstitucionEducativa,
  isActionDisabledByInstitucionEducativa
} from "../../../utils";

const MisSolicitudes = ({ totalInit }) => {

  const { useState, useEffect } = React
  const [ camposClinicos, setCamposClinicos ] = useState([])
  const [ search, setSearch ] = useState('')
  const [ tipoPago, setTipoPago ] = useState(null)
  const [ total, setTotal ] = useState(totalInit)
  const [ currentPage, setCurrentPage ] = useState(1)
  const [ isLoading, toggleLoading ] = useState(false)

  useEffect(() => {
    if(currentPage !== null || tipoPago !== null) getCamposClinicos()
  }, [currentPage, tipoPago])

  function handleSearch() {
    if(!search) return;
    getCamposClinicos()
  }

  function getCamposClinicos() {
    toggleLoading(true)

    solicitudesGet(
      tipoPago,
      currentPage,
      search
    ).then((res) => {
        setCamposClinicos(res.camposClinicos)
        setTotal(res.total)
      })
      .finally(() => toggleLoading(false))
  }

  function handleStatusAction(solicitud) {
    if(isActionDisabledByInstitucionEducativa(solicitud.estatus)) return;

    let redirectRoute = ''

    switch(solicitud.estatus) {
      case SOLICITUD.CONFIRMADA:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/registrar-montos`
        break
      case SOLICITUD.MONTOS_VALIDADOS_CAME:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/seleccionar-forma-de-pago`
        break
      case SOLICITUD.FORMATOS_DE_PAGO_GENERADOS:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/detalle-de-forma-de-pago`
        break
      case SOLICITUD.CARGANDO_COMPROBANTES:
        redirectRoute = `/ie/solicitudes/${solicitud.id}/detalle-de-solicitud-multiple`
        break
    }

    window.location.href = redirectRoute
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
            <option value=''>Ver todos</option>
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
                  camposClinicos.map((solicitud, index) => (
                    <tr key={index}>
                      <th><a href="">{solicitud.noSolicitud}</a></th>
                      <th>{solicitud.noCamposSolicitados}</th>
                      <th>{solicitud.noCamposAutorizados}</th>
                      <th>{solicitud.fecha}</th>
                      <th>{solicitud.tipoPago}</th>
                      <th>{solicitud.estatus}</th>
                      <th>
                        <button
                          className='btn btn-default'
                          disabled={isActionDisabledByInstitucionEducativa(solicitud.estatus)}
                          onClick={() => handleStatusAction(solicitud)}
                        >
                          {getActionNameByInstitucionEducativa(solicitud.estatus, solicitud.tipoPago)}
                        </button>
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

export default MisSolicitudes
