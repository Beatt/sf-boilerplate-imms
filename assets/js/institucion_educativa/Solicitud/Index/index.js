import * as React from 'react'
import ReactDOM from 'react-dom'
import ReactPaginate from 'react-paginate';
import { solicitudesGet } from "../../api/solicitud";

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
    if(currentPage !== null || tipoPago !== '') {
      getCamposClinicos()
    }
  }, [currentPage, tipoPago])

  const ESTATUS_TEXTS = {
    solicitud_creada: {
      title: 'Nueva',
      button: 'Registrar montos'
    },
    en_espera_de_validacion_de_montos: {
      title: '',
      button: 'Corrija montos'
    },
    montos_validados: {
      title: 'Montos validados',
      button: 'Consulte formatos de pago'
    },
    pago_en_proceso: {
      title: '',
      button: 'Cargue comprobante de pago'
    },
    pagado: {
      title: '',
      button: 'Dercargue factura'
    },
    en_validacion_por_fofoe: {
      title: '',
      button: 'Corrija montos'
    },
    solicitud_no_autorizada: {
      title: 'ese',
      button: 'ese'
    }
  }

  const TIPO_PAGO = {
    UNICO: 'unico',
    INDIVIDUAL: 'individual'
  }

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
          <label htmlFor="">Tipo de pago</label>
          <div className="row">
            <div className='col-md-4'>
              <label htmlFor="tipo_de_pago_unico">Único&nbsp;</label>
              <input
                type="radio"
                id='tipo_de_pago_unico'
                name='tipoDePago'
                value={TIPO_PAGO.UNICO}
                defaultChecked={true}
                onChange={({ target }) => setTipoPago(target.value)}
              />
            </div>
            <div className='col-md-6'>
              <label htmlFor="tipo_de_pago_individual">Individual&nbsp;</label>
              <input
                type="radio"
                id='tipo_de_pago_individual'
                name='tipoDePago'
                value={TIPO_PAGO.INDIVIDUAL}
                onChange={({ target }) => setTipoPago(target.value)}
              />
            </div>
          </div>
        </div>
      </div>
      <div className="col-md-3">
        <div className="form-group">
          <label htmlFor="status">Estado</label>
          <select
            name=""
            id="status"
            className='form-control'
          >
            <option value="">Elige una opción</option>
            <option value="Nueva">Nueva</option>
            <option value="Some">Some</option>
          </select>
        </div>
      </div>
      <div className="col-md-1"/>
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
                      <th>{ESTATUS_TEXTS[campoClinico.estatusActual].title}</th>
                      <th>
                        <button className='btn btn-default'>
                          {ESTATUS_TEXTS[campoClinico.estatusActual].button}
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

document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Index
      totalInit={window.CAMPOS_CLINICOS_TOTAL_PROPS}
      institucionId={window.INSTITUCION_ID_PROPS}
    />,
    document.getElementById('solicitud-index-component')
  )
})
