import * as React from 'react'
import ReactDOM from 'react-dom'
import ReactPaginate from 'react-paginate';

const Index = ({ camposClinicosInit, total, institucionId }) => {

  const { useState } = React
  const [ camposClinicos, setCamposClinicos ] = useState(camposClinicosInit)
  const [ isLoading, toggleLoading ] = useState(false)

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
            />
          </div>
          <button type="button" className="btn btn-default">Buscar</button>
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
              camposClinicos.map(campoClinico => (
                <tr>
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
          marginPagesDisplayed={1}
          pageRangeDisplayed={5}
          onPageChange={(currentPage) => {
            toggleLoading(true)
            fetch(`/instituciones/${institucionId}/solicitudes?offset=${currentPage.selected}`)
              .then(function(response) {
                return response.json();
              })
              .then(function(myJson) {
                setCamposClinicos(myJson.camposClinicos)
                toggleLoading(false)
              });
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
      total={window.CAMPOS_CLINICOS_TOTAL_PROPS}
      institucionId={window.INSTITUCION_ID_PROPS}
    />,
    document.getElementById('solicitud-index-component')
  )
})
